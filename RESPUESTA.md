# Problemas encontrados

### Extras:

- Se encontraron los siguientes inconvenientes en las migraciones donde se cambia de tipo `->text(...)` a `->enum(...)` ya que no es rentable hacer uso de un texto que se puede alterar rápidamente, y es mejor tener valores establecidos de inicio:
   - `create_alumnos_table`
   - `create_inscripciones_table`
   - `create_pagos_table`
   - `create_reporte_deudas_table`
 - También se cambia de tipo `->float(...)` a `->decimal(...)` ya que es el adecuado para montos, precios, dinero,
    - code: `$table->decimal('inscripcion', 11, 2)->default(0);`
    - migraciones:
      - `create_programas_table`
      - `create_adeudos_table` 
- Se realiza cambio en migracion de create_alumnos_table, donde se cambia ->text(...) por ->string(...) ya que esto consume más recursos y no es rentable para este tipo de campos donde no habrá mucha información y el campo `curp` y `email` se agrega ->unique(...):
- 
    `$table->string('matricula');
    $table->string('nombre_completo');
    $table->string('apat');
    $table->string('amat');
    $table->string('curp')->unique();
    $table->string('email')->unique();
    $table->string('telefono')->nullable();`

- se agregan los indices en las tablas: alumnos(matricula, sede_id), pagos(matricula), inscripciones(alumno_id)
- Se crea service de alumnoController para tener mayor legibilidad y mantenibilidad del codigo a futuro, se pasa logica al controller y despues se inyecta en el appServiceProvider
   
### Auth:

DashboardController@index, se hace cambio al obtener `$programasTop` ya que esto es mala practica traer todos
los datos de golpe ya que no sabemos si son miles y puede volverse lento y consumir demasiada memoria,
se agrega formato `number_format()` en pagos del mes.

- antes:
- 
    - `$programasTop = Programa::all()->sortByDesc(function($p) {
            return $p->inscripciones->count();
        })->take(5);`
- después:
- 
    - `$programasTop = Programa::withCount('inscripciones')
        ->orderBy('inscripciones_count', 'desc')
        ->take(5)
        ->get();`

Se mueve la lógica a los modelos, ahí es donde pertenece y se hace un código más legible en el controller, así mismo aplicamos `DRY` y evitar repetir el mismo scope en otras partes del sistema:

    `$alumnosActivos = Alumno::activosCount();`
    `$totalAlumnos = Alumno::totalAlumnos();`
    `$pagosMes = Pago::activos()->delMesActual()->sum('monto');`
    `$inscripcionesPendientes = Inscripcion::activos()->get();`

También se evita mandar a llamar todos los campos de `$pagos` ya que consume más recursos y solamente se necesita un campo para trabajar: `$pagos = Pago::all('monto');`


### Pagos:

PagosController@store se agrega validacion en el request mas detallado donde se valida que el usuario elija un usuario valido del select estatico de `metodo` y tambien se valida que exista el usuario seleccionado en la tabla de `alumnos`, montos negativos, 
se valida que el listado de alumnos, que no se repitan, que esten activos

- `$request->validate([
        'matricula' => 'required|string|max:255|exists:alumnos,matricula',
        'concepto' => 'required',
        'monto' => 'numeric|min:0|max:999999.99',
        'fecha_pago' => 'required|date|before_or_equal:today|after_or_equal:' . now()->subMonth()->toDateString(),
        'metodo' => 'in:efectivo,transferencia,tarjeta',
        'sede_id' => 'required|numeric|min:0'
    ]);`

- Se agrega la directiva `@method('POST')` en caso que el usuario manipule directamente el formulario
- Se agregan las directivas de `@error` para en caso que el usuario registre algun dato mal le avise y corrija y no se pierda.

Se asegura que ahora en el monto se pueda guardar con 2 decimales y no 3: `$comision = round($request->input('monto') * 0.05, 2);`

Se agrega validacion que no se pueden eliminar alumnos con adeudos pendientes: `$alumno->pagos()->where('estado', 'activo')->count() > 0`

### Dashboard:

DashboardController@index, aqui se agrega cache para aquellos valores que no es necesario cargar cada que el usuario recargue la pagina, en esta ocasion se actualiza cada 5 min, se remueve el key de cache y se actualiza

- `$promedioPago = Cache::remember('promedio_pago', 300, function () use ($pagos, $suma) {
        return count($pagos) > 0 ? $suma / count($pagos) : 0;
    });`


- `$programasTop = Cache::remember('programas_top', 300, function () {
        return Programa::withCount('inscripciones')
            ->where('nombre', '!=', '')
            ->having('inscripciones_count', '>', 0)
            ->orderBy('inscripciones_count', 'desc')
            ->take(5)
            ->get();
    });`


### Alumnos:

AlumnoController@index, se agrega validacion de alumnos, paginacion y se muestran sedes validas,
@destroy, se agrega validacion siempre y cuando exista el alumno a eliminar, mostrar una alerta

- se modifica la forma de eliminar alumno ya que usaba un <a> lo cual es inseguro porque la url venia con GET
  - se cambia a DELETE y se agrega confirmacion de eliminar alumno con SweetAlert2
  - se agrega CSRF 

- AlumnoController@store, se crea refactor ya que no se hacia uso de `DB::beginTransaction();` // `DB::commit();`, lo cual puede ser peligroso en produccion, cualquier error y puede haber datos inconsistentes en la base de datos, esto se agrega para en caso de cual error se regresen los cambios a como estaban de inicio
- se agregan validacion en los inputs y se coloca directiva @error en el blade


### API:

AlumnoApiController@show / @pagos, se agrega mejora considerable porque antes no validaba el formato de matricula, tampoco
en caso de no encontrar al alumno, saber si el usuario estaba activo o no, sin manejo de excepciones, se pueden tambien 
agregar mensajes falsos-positivos, donde tampoco es bueno especificar demasiado el error ya que atacantes pueden
guiarse de eso y facilitarle un acceso no autorizado.


### JOBS:

- `GenerarReporteDeudasJob`, se agrega chunk de cada 200 registros, se manejan excepciones en caso que algo fallara se registra en el Log con los detalles del error, el chunk se utiliza para no saturar la DB de cientos o miles de operaciones por segundo


### Test:

Se crea un test para saber si el alumno esta duplicado por: matricula, mail, curp


### ErroresHumanosSeeder -- no time xc
