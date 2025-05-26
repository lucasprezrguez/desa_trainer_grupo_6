# Simulador de Entrenamiento DESA en Laravel

## Descripción General del Proyecto

Este proyecto es un simulador de entrenamiento basado en web para Desfibriladores Externos Automáticos (DEA), también conocidos como DESA (Desfibrilador Externo Semiautomático/Automático) en algunas regiones. Está construido utilizando el framework PHP Laravel. Su objetivo principal es proporcionar una plataforma accesible e interactiva para que las personas aprendan y practiquen los procedimientos operativos correctos de un DESA en escenarios de emergencia simulados.

## Propósito

El paro cardíaco súbito (PCS) es una condición potencialmente mortal donde el corazón deja de latir repentinamente. La reanimación cardiopulmonar (RCP) inmediata y el uso de un DESA pueden aumentar significativamente las posibilidades de supervivencia. Sin embargo, muchas personas no están familiarizadas con el uso de un DESA o se sienten intimidadas por él. Este simulador tiene como objetivo cerrar esa brecha ofreciendo un entorno virtual seguro para:

*   **Educar:** Enseñar a los usuarios el proceso paso a paso para operar un DESA.
*   **Fomentar la Confianza:** Permitir a los usuarios practicar el uso del DESA repetidamente, ayudándoles a sentirse más cómodos y seguros en su capacidad para usar uno en una emergencia real.
*   **Mejorar la Preparación:** Equipar a las personas con el conocimiento y las habilidades para responder eficazmente durante un evento de paro cardíaco.

## Características Principales

El Simulador de Entrenamiento DESA incluirá los siguientes módulos y funcionalidades clave:

*   **Gestión de Usuarios:**
    *   **Registro:** Permite a los nuevos usuarios crear una cuenta.
    *   **Inicio/Cierre de Sesión:** Autenticación segura para que los usuarios accedan a sus perfiles y materiales de entrenamiento.
    *   **Roles y Permisos:**
        *   **Alumno:** Puede acceder a escenarios de entrenamiento y seguir su propio progreso.
        *   **Profesor (Funcionalidad Futura):** Podría tener capacidades para gestionar grupos de alumnos, asignar escenarios y ver el progreso de los alumnos.
        *   **Administrador:** Gestiona cuentas de usuario, configuraciones del sitio y contenido de los escenarios.

*   **Gestión de Escenarios (Principalmente para Administradores/Profesores):**
    *   **Crear Escenarios:** Desarrollar nuevos escenarios de entrenamiento con diferentes condiciones, estados del paciente (por ejemplo, ritmos desfibrilables vs. no desfibrilables) y factores ambientales.
    *   **Editar Escenarios:** Modificar escenarios existentes para actualizar información o ajustar la dificultad.
    *   **Eliminar Escenarios:** Eliminar escenarios obsoletos o irrelevantes.
    *   **Biblioteca de Escenarios:** Un repositorio de escenarios disponibles para el entrenamiento.

*   **Simulador de Entrenamiento Interactivo:**
    *   Este es el módulo central donde los alumnos interactúan con dispositivos DESA/DEA simulados.
    *   Proporciona una guía paso a paso de la operación del DESA basada en los escenarios seleccionados.
    *   Ofrece retroalimentación y guía en tiempo real como se describe en "Características Centrales del Simulador" a continuación.

*   **Seguimiento de Progreso e Informes:**
    *   **Progreso Individual:** Los alumnos pueden ver su historial de escenarios completados, métricas de rendimiento (por ejemplo, tiempo para completar pasos, precisión en la colocación de los parches) y áreas de mejora.
    *   **Certificados de Finalización (Funcionalidad Futura):** Posibilidad de generar certificados tras la finalización exitosa de módulos de entrenamiento específicos.
    *   **Panel del Profesor (Funcionalidad Futura):** Para que los profesores monitoreen el progreso de sus alumnos o grupos.

## Características Centrales del Simulador

El Simulador de Entrenamiento DESA en Laravel ofrecerá las siguientes funcionalidades específicas de simulación:

*   **Escenarios Realistas:** Simular diversas situaciones de paro cardíaco, incluyendo potencialmente diferentes condiciones del paciente y factores ambientales.
*   **Guía Interactiva:** Proporcionar instrucciones claras en pantalla y señales visuales para guiar a los usuarios a través de cada paso de la operación del DESA, tales como:
    *   Encender el DESA.
    *   Colocar correctamente los parches del desfibrilador en un maniquí virtual.
    *   Seguir las indicaciones de voz y visuales del DESA simulado.
    *   Comprender cuándo y cómo administrar una descarga (si lo aconseja el simulador).
    *   Saber cuándo reanudar la RCP.
*   **Retroalimentación al Usuario:** Ofrecer retroalimentación inmediata sobre las acciones del usuario, destacando los procedimientos correctos y las áreas de mejora.
*   **Variación de Escenarios (Objetivo Futuro):** Permitir potencialmente a Profesores o usuarios personalizar escenarios o seleccionar de una biblioteca de situaciones predefinidas para mejorar el aprendizaje.
*   **Accesibilidad Basada en Web:** Al ser una aplicación web, se puede acceder al simulador desde la mayoría de los navegadores web modernos sin necesidad de hardware especializado o instalaciones de software. Esto lo hace fácilmente disponible para estudiantes individuales, centros de formación y organizaciones.
*   **Soporte Multilingüe (Funcionalidad Futura):** Dado que "DESA" es un término utilizado en regiones de habla hispana, el desarrollo futuro podría incluir capacidades multilingües para atender a una audiencia más amplia.

## Público Objetivo

Este simulador está diseñado para:

*   Individuos que buscan aprender o refrescar sus habilidades en DESA/DEA como parte de la formación en RCP y primeros auxilios.
*   Socorristas y profesionales de la salud que requieren práctica regular de competencia en DESA/DEA.
*   Profesores y organizaciones de formación que buscan una herramienta complementaria para sus cursos.
*   Lugares de trabajo y espacios públicos que tienen como objetivo capacitar al personal sobre cómo usar un DESA/DEA en el sitio.

## Tecnologías Utilizadas

Este proyecto aprovecha una gama de tecnologías web modernas para ofrecer una experiencia robusta e interactiva:

*   **Backend:**
    *   **PHP:** El principal lenguaje de scripting del lado del servidor.
    *   **Framework Laravel:** Un potente y elegante framework PHP que proporciona la estructura central, enrutamiento, ORM (Eloquent) y más.
    *   **Laravel Fortify:** Utilizado para la lógica de autenticación del backend (registro, inicio de sesión, restablecimiento de contraseña).
    *   **Laravel Jetstream:** Proporciona el andamiaje de la aplicación, incluida la gestión de perfiles de usuario y el soporte de API.

*   **Frontend:**
    *   **HTML:** Para estructurar el contenido web.
    *   **CSS:** Para estilizar la interfaz de usuario.
    *   **JavaScript:** Para la interactividad del lado del cliente y características dinámicas dentro del simulador.
    *   **Motor de Plantillas Blade:** El motor de plantillas nativo de Laravel para crear vistas dinámicas.
    *   **Tailwind CSS:** Un framework CSS de utilidad primordial utilizado para el estilizado (inferido de `tailwind.config.js`).
    *   **Vite:** Una moderna herramienta de compilación de frontend para compilar activos como JavaScript y CSS (inferido de `vite.config.js`).
    *   **AdminLTE:** Una popular plantilla de panel de administración utilizada para la interfaz administrativa (inferido de `config/adminlte.php`).
    *   **Bootstrap:** Un framework CSS probablemente utilizado por AdminLTE y potencialmente para otros componentes de frontend.

*   **Base de Datos:**
    *   **Base de Datos Relacional:** El ORM Eloquent de Laravel es compatible con varias bases de datos. La base de datos específica (por ejemplo, MySQL, PostgreSQL, SQLite) se definiría en la configuración del entorno.

*   **Desarrollo y Despliegue:**
    *   **Composer:** Gestor de dependencias de PHP.
    *   **NPM/Yarn:** Gestor de paquetes de JavaScript (entorno Node.js).
    *   **Servidor Web:** Típicamente desplegado en servidores como Apache o Nginx.

## Instalación y Configuración

Siga estos pasos para poner en funcionamiento el Simulador de Entrenamiento DESA en su entorno de desarrollo local.

**Prerrequisitos:**

*   PHP (versión especificada en `composer.json`, típicamente la última versión estable)
*   Composer (gestor de dependencias de PHP)
*   Node.js y NPM (entorno de ejecución de JavaScript y gestor de paquetes)
*   Un servidor de base de datos (por ejemplo, MySQL, PostgreSQL, MariaDB)

**Pasos:**

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/lucasprezrguez/desa_trainer_grupo_6.git
    cd simulador-desa-laravel
    ```

2.  **Instalar Dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Instalar Dependencias de JavaScript:**
    ```bash
    npm install
    ```

4.  **Configurar el Archivo de Entorno:**
    *   Copie el archivo de entorno de ejemplo:
        ```bash
        cp .env.example .env
        ```
    *   Abra el archivo `.env` en un editor de texto y configure los detalles de conexión de su base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.) y cualquier otra variable de entorno necesaria (por ejemplo, APP_URL, MAIL_MAILER).

5.  **Generar Clave de Aplicación:**
    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar Migraciones de Base de Datos:**
    Esto creará las tablas necesarias en su base de datos.
    ```bash
    php artisan migrate
    ```

7.  **Ejecutar Sembradores de Base de Datos (Recomendado):**
    Esto poblará la base de datos con datos iniciales (por ejemplo, usuario administrador, escenarios de ejemplo, instrucciones). El proyecto incluye sembradores para `UserSeeder`, `InstructionsSeeder`, `ScenarioSeeder` y `ScenarioInstructionSeeder`.
    ```bash
    php artisan db:seed
    ```

8.  **Construir Activos de Frontend:**
    (Este paso puede variar según la configuración del proyecto, pero `npm run dev` es común para el desarrollo)
    ```bash
    npm run dev
    ```
    (Para producción, podría usar `npm run build`)

9.  **Iniciar el Servidor de Desarrollo:**
    ```bash
    php artisan serve
    ```
    La aplicación debería estar accesible ahora en `http://localhost:8000` (o la URL especificada en su archivo `.env` o por el comando `serve`).

Ahora debería tener una instancia en funcionamiento del Simulador de Entrenamiento DESA.

## Contribuciones

¡Las contribuciones son bienvenidas y muy apreciadas! Este proyecto prospera con la participación de la comunidad. Así es como puede contribuir:

**Formas de Contribuir:**

*   **Reportar Errores:** Si encuentra un error, por favor abra un "issue" en el repositorio de GitHub. Incluya tantos detalles como sea posible: pasos para reproducir, comportamiento esperado, comportamiento real y su entorno.
*   **Sugerir Mejoras:** ¿Tiene una idea para una nueva característica o una mejora a una existente? Abra un "issue" para discutirla.
*   **Enviar Pull Requests:** Si desea contribuir con código, siga estos pasos:

**Proceso de Pull Request:**

1.  **Hacer un Fork del Repositorio:** Cree su propio fork del proyecto en GitHub.
2.  **Crear una Rama:** Cree una nueva rama en su fork para su característica o corrección de error. Use un nombre descriptivo (por ejemplo, `feature/nuevo-tipo-escenario` o `bugfix/error-login`).
    ```bash
    git checkout -b feature/tu-nombre-de-funcionalidad
    ```
3.  **Realizar Cambios:** Haga sus cambios en el código base.
4.  **Confirmar Cambios (Commit):** Confirme sus cambios con mensajes de commit claros y concisos.
    ```bash
    git commit -m "Añadir: Implementar nuevo tipo de escenario X"
    git commit -m "Corregir: Resolver error de login para roles de usuario específicos"
    ```
5.  **Adherirse a los Estándares de Codificación (Si Aplica):** Si el proyecto tiene estándares de codificación específicos (por ejemplo, PSR-12 para PHP, o linters para JS/CSS), por favor asegúrese de que su código se ajuste. (Una nota general, se pueden añadir directrices más específicas más adelante).
6.  **Probar Sus Cambios:** Asegúrese de que sus cambios no rompan la funcionalidad existente. Si es posible, añada pruebas para su nuevo código.
7.  **Enviar a Su Fork (Push):** Envíe sus cambios a su repositorio fork.
    ```bash
    git push origin feature/tu-nombre-de-funcionalidad
    ```
8.  **Abrir un Pull Request:** Vaya al repositorio original del proyecto y abra un pull request desde su rama fork a la rama principal del proyecto (por ejemplo, `main` o `develop`).
    *   Proporcione un título y una descripción claros para su pull request, explicando los cambios que ha realizado y por qué.
    *   Referencie cualquier "issue" relevante.

**¿Preguntas?**

Si tiene alguna pregunta, no dude en abrir un "issue" o contactar a los mantenedores.

¡Esperamos sus contribuciones!

## Licencia

Este proyecto está licenciado bajo la Licencia MIT.

La Licencia MIT es una licencia de software libre permisiva originada en el Instituto Tecnológico de Massachusetts (MIT). Permite la reutilización dentro de software propietario siempre que todas las copias del software licenciado incluyan una copia de los términos de la Licencia MIT y el aviso de copyright. Dicho software licenciado también puede ser distribuido bajo una licencia diferente, como una licencia propietaria, o una licencia copyleft como la GPL.

Puede encontrar una copia de la licencia en el archivo `LICENSE` en el directorio raíz de este árbol de fuentes.

---

Este proyecto de Simulador de Entrenamiento DESA/DEA se esfuerza por hacer que el conocimiento que salva vidas sea más accesible y por empoderar a más personas para que actúen con decisión en situaciones críticas.
