<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../");
}

$mtto = new mtto();

$id = $mysqli->real_escape_string($_GET['id']);

$procedure = $mtto->findProcedure($id);

?>
<style>
    *, *::before, *::after {
        box-sizing: border-box;
    }
</style>
<page backtop="25mm" backttom="15mm" backleft="4mm" backright="4mm">
    <page_header>
        <div style="padding: 0 40px">
            <table style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="width: 25%; border: 1px solid white;"><img src="img/LOGO.png"
                                                                                      style="position: relative; width: 150px; height: 50px;">
                    </td>
                    <td rowspan="3"
                        style="width: 368px; vertical-align: middle; border: 1px solid black; font-weight: bold; text-align:center; padding: 0 10px"><?php echo $procedure['title'] ?></td>
                    <td style="width: 25%; border: 1px solid black; font-size: 11px;">Código: F-270</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Fecha: <?php echo $procedure['date'] ?></td>
                </tr>

            </table>
        </div>
    </page_header>
    <div style="padding: 0 27px">
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>1. OBJETIVO</b></h5>
            <div><?php echo $procedure['objective'] ?></div>
        </div>
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>2. ALCANCE</b></h5>
            <div><?php echo $procedure['scope'] ?></div>
        </div>
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>3. DEFINICIONES</b></h5>
            <div><?php echo $procedure['definitions'] ?></div>
        </div>
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>4. RESPONSABLES</b></h5>
        <br>
    </div>
    <table style="width: 100%;border-collapse: collapse;">
        <thead>
        <tr>
            <th style="width: 20%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CARGO</th>
            <th style="width: 30%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CANTIDAD DE
                TRABAJADORES POR CUADRILLA
            </th>
            <th style="width: 50%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">
                RESPONSABILIDADES
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 20%; text-align: center; border: 1px solid;"><?php echo $procedure['position_1'] ?></td>
            <td style="width: 30%; text-align: center; border: 1px solid;"><?php echo $procedure['number_workers_1'] ?></td>
            <td style="width: 50%; text-align: center; border: 1px solid;"><?php echo $procedure['responsibilities_1'] ?></td>
        </tr>
        <tr>
            <td style="width: 20%; text-align: center; border: 1px solid;"><?php echo $procedure['position_2'] ?></td>
            <td style="width: 30%; text-align: center; border: 1px solid;"><?php echo $procedure['number_workers_2'] ?></td>
            <td style="width: 50%; text-align: center; border: 1px solid;"><?php echo $procedure['responsibilities_2'] ?></td>
        </tr>
        </tbody>
    </table>
    <div style="padding-left: 20px">
        <div style="padding: 0 27px; width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>5. RECOMENDACIONES</b></h5>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div><?php echo $procedure['recommendations'] ?></div>
                    </td>
                </tr>
            </table>
            <div style="padding-left: 10px; margin-bottom: 20px">
                <h5 style="margin: 0; padding: 0; font-size: 15px"><b>5.1 USO CORRECTO DE ELEMENTOS DE PROTECCIÓN
                        PERSONAL</b></h5>
                <div style="padding-top: 20px; text-align: center">
                    <img src="img/procedimiento-image.png" style="width: 550px;">
                </div>
            </div>
        </div>
    </div>

    <div style="padding-left: 20px">
        <div style="padding-left: 10px; margin-bottom: 20px; width: 560px">
            <h5 style="margin: 0; padding: 0; font-size: 15px">
                <b>6. PROCEDIMIENTO / DESCRIPCIÓN DE LA ACTIVIDAD</b>
            </h5>
            <div style="padding-left: 10px">
                <h5 style="margin: 10px 0 0 0; padding: 0 0 0 10px; font-size: 15px"><b>6.1 Planeación</b></h5>
                <div style="padding-top: 20px;">
                    <?php echo $procedure['planning'] ?>
                </div>
            </div>
        </div>
    </div>
    <div style="padding-left: 20px">
        <div style="padding-left: 10px; width: 560px">
            <div style="padding-left: 10px">
                <h5 style="margin: 10px 0 0 0; padding: 0 0 0 10px; font-size: 15px">
                    <b>6.2 Ejecución del Mantenimiento preventivo al Acumulador</b>
                </h5>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px"><b>6.2.1 Mantenimiento
                            mensual</b></h5>
                </div>
                <div>
                    <?php echo $procedure['monthly_maintenance'] ?>
                </div>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px"><b>6.2.2 Mantenimiento
                            semestral</b></h5>
                </div>
                <div>
                    <?php echo $procedure['semi_annual_maintenance'] ?>
                </div>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px">
                        <b>6.2.3 Mantenimiento cada 2 años
                        </b>
                    </h5>
                </div>
                <div>
                    <?php echo $procedure['maintenance_2_years'] ?>
                </div>
            </div>
        </div>
    </div>

    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>7. EQUIPOS Y HERRAMIENTAS</b></h5>
            <div>
                <?php echo $procedure['equipment_tools'] ?>
            </div>
        </div>
    </div>
    <br>
    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>8. REGISTROS</b></h5>
            <div>
                <?php echo $procedure['records'] ?>
            </div>
        </div>
    </div>
    <br>
    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>9. PELIGROS, RIESGOS Y CONTROLES</b></h5>
        </div>
    </div>
    <table style="font-size: 12px;width: 100%; border-collapse: collapse">
        <thead>
        <tr style="font-size: 12px; text-align: center;">
            <th colspan="2" style="border: 1px solid black;text-align: center;padding: 5px">PELIGRO</th>
            <th colspan="3" style="border: 1px solid black;text-align: center;padding: 5px">CONTROLES EXISTENTES</th>
        </tr>
        <tr style="font-size: 12px; text-align: center">
            <th style="width: 110px; border: 1px solid black;padding: 5px">DESCRIPCIÓN</th>
            <th style="width: 110px; border: 1px solid black;padding: 5px">CLASIFICACIÓN</th>
            <th style="width: 110px; border: 1px solid black;padding: 5px">FUENTE</th>
            <th style="width: 110px; border: 1px solid black;padding: 5px">MEDIO</th>
            <th style="width: 170px; border: 1px solid black;padding: 5px">INDIVIDUO</th>
        </tr>
        </thead>
        <tbody>
        <tr style="font-size: 12px;">
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Interacción de personal durante la pandemia
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>BIOLÓGICO:</b>
                Covid-19
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Implementación del Protocolo de
                Bioseguridad, desinfección de
                áreas comunes, distanciamiento
                social en áreas de alimentación y
                servicio de transporte
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente al
                riesgo.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso correcto de elementos de Bioseguridad (Tapabocas,
                guantes,lavado de manos), capacitación y
                entrenamiento en protocolos de bioseguridad.
                Implementación de reporte de condiciones de salud,
                implementación de pruebas PCR
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Presencia estabilizadores,
                sistemas escualizablesde
                barandas, motores de
                centrífuga, winche,
                compresor, malacates
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Mecánico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Disposición de guardas de
                seguridad, Encerramientos
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Señalización de
                advertencia.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación Prevención Riesgo Mecánico; Hacer
                socialización con lostrabajadores sobre los riesgos
                existentes, controles y cuidados al iniciar actividades en
                el área.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Equipo de levantamiento
                mecánico de cargas(Winche -
                Bloque viajero)
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>Condiciones de
                    seguridad:</b>
                Mecánico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control de toneladas millas, para
                el cable malacate principal.
                Inspeccióndiaria del cable del
                winche.
                Inspecciones periódicas
                certificadasNivel III y IV, sistemas
                de parada deemergencia.
                Identificación de la zona por
                potencial de caída de objetos.
                Implementación Programa
                DROPS. Implementación del
                Programa de mantenimiento
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente a
                riesgo mecánico,
                identificación de la
                capacidad de los equipos.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Maquinistas entrenados y capacitados como
                Operadores de winche, Capacitación y entrenamiento
                de todo elpersonal en Riesgo mecánico.
                Formación en competencias del personal bajo el
                programa de disciplinaoperativa (Aspectos técnicos y de
                seguridad para operar sistemas de levante)
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Objetos bajo tensiónmecánica
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>Condiciones de
                    seguridad:</b>
                Mecánico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Implementar programa de
                mantenimiento, Control toneladas
                millas para cable malacate
                principal, inspección diaria cable
                del winche, inspecciones
                periódicas certificadas Nivel III y
                IV, Sistemas de parada de
                emergencia,parámetros de
                operación acorde a la capacidad
                de la RSU, tubería y varilla
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente a
                riesgo mecánico,
                identificación de la
                capacidad de los equipos.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Maquinistas entrenados y capacitados como
                Operadores de winche, Capacitación y entrenamiento
                de todo elpersonal en Riesgo mecánico.
                Formación en competencias del personal bajo el
                programa de disciplinaoperativa (Aspectos técnicos y de
                seguridad para operar sistemas de levante)
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Caídas de objetos
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>Condiciones de
                    seguridad:</b>
                Mecánico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Instalar aseguramiento primario y
                secundario de los componentes
                móviles existentes en el mástil y
                demás componentes de la RSU.
                Aplicar F-212 Lista de chequeo
                para el procedimiento de
                prevención de caída de objetos.
                F-211 Matriz para prevención de
                caída de objetos.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente a
                riesgo por Caída de
                objetos y la clasificación de
                las zonas conpotencial de
                caída de objetos.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación y entrenamiento en elprograma DROPS,
                planeación de simulacros, uso de casco tipo safariMSA
                Clase E Tipo I Bajo la Norma ANZI Z89.1.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Intensidad de luz nouniforme
                en el área.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>FÍSICO:</b>
                Iluminación
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Estudios de luxometría
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Ubicación correcta de las
                lámparas de planta
                estadio.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Visiometrías en exámenes médicosocupacionales
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Trabajos en altura mayor a
                1,5 metros
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Trabajo en alturas
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Instalación de sistema de
                protección contra caídas y
                equipos de rescate
                inspeccionados y certificados
                acordes a la necesidadde la
                operación (Línea de vida vertical
                fija, retráctil 50 ft). Sistemas
                colectivos para protección de
                caídas (barandas),
                inspeccionados y certificados.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente al
                riesgopor trabajo en
                alturas,
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación y entrenamiento en TSA Avanzado,
                conformación de brigadas de emergencia, elementosde
                protección contra caídas (Arnés de cuerpo completo,
                eslingas con absorbedor de caídas, casco tipo II,eslinga
                de posicionamiento, eslingaRebar). Inspeccionados y
                certificados acordes a la Resolución1409/2012.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Partes en movimiento o
                rotativas (Piezas y partes
                rotativas de las herramientas,
                equipos y motores).
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Mecánico.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Sistemas de bloqueo en
                herramientas neumáticas,
                hidráulicas. Guardas para
                partes rotativas de
                herramientas y equipos.
                Identificación de puntos
                seguros de agarre.
                Herramientas con puntos de
                agarre ergonómico.
                Instalación de SAS y SAES en
                equipos a intervenir.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente a
                riesgomecánico.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                EPP: Guantes de carnaza reforzados, capacitación y
                entrenamiento del personal en Riesgo mecánico.
                Formación en competencias del personal bajo el
                programa de disciplina operativa (Aspectos técnicos y de
                seguridadpara el uso de herramientas)
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Inhalación y/o exposición de
                vaporesorgánicos y gases
                ácidos. Reacción química
                (ignición) de gases y
                vapores combustibles e
                inflamables. Inhalaciónde
                gases tóxicos.
                Nocivo / Irritación envías
                respiratorias / Contacto
                térmico - Quemaduras.
                Puede explotar si secalienta.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>QUÍMICO:</b>
                Polvos
                orgánicos – Gases y
                vapores
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Monitoreo de atmósferas con
                equipo fijo y portátil con
                calibraciónvigente.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Ubicar señalización,
                sensores de H2S con
                alarma visual y sonora
                calibrados a 5 ppm,
                mangaveleta para
                identificar la dirección del
                viento, sistema de
                ventilación, disponibilidad
                de equipamiento para
                control de emergencias
                (Equipo auto contenido)
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Set de mascarillas para vapores orgánicos emergencia
                por H2S paraevacuación, Conformación y entrenamiento
                de las brigadas de emergencia, divulgación de los
                análisis de riesgos y procedimientos, Supervisor y
                maquinista entrenados para control de pozos.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Accidente por Desplazamiento
                vehicular
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Accidentes de
                Tránsito
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control de velocidad (GPS) en los
                vehículos de campo y Programa
                deMantenimiento a equipos,
                vehículos y maquinaria.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Señalización vial
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento de vehículos, Inspección preoperacional
                de vehículos, Capacitación sobre Programa de manejo
                defensivo,Campaña Vial.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Objetos y/o superficies
                irregulares / Ascenso y
                descenso de escaleras
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Locativo
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Escaleras y barandas
                inspeccionadas, certificadas de
                acuerdo con la Res. 1409 / 2012.
                Implementación del formato F-227
                Inspección pre-uso de escaleras.
                Reporte al cliente de los hallazgos
                encontrados en la visita previa
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente al
                correctouso de escaleras,
                delimitacióny señalización
                del área.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación y entrenamiento en eluso seguro de
                escaleras.
                Socialización de los análisis de riesgos para el servicio
                específico apozo.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Exposición a nivelesmayores
                de los permisibles (Disconfort
                o estrés auditivo)
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>FÍSICO:</b>
                Ruido
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Equipos insonorizados,
                realizaciónde mediciones
                higiénicas anuales.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografías referentes al
                riesgode exposición a
                ruido y elementos de
                protección requeridos,
                audiometrías, dosimetrías.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso de protección auditiva de inserción acorde a los
                estudioshigiénicos.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Altas temperaturas (Calor
                ambiental / Cerca a teas /
                Superficies Calientes)/
                Exposición al sol
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>FÍSICO:</b>
                Exposición a
                radiaciones no
                ionizantes (solar) y
                Temperaturas
                extremas (Calor o
                frio)
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Suministro de ventilación en boca
                de pozo.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Zona de hidratación
                cubierta,Infografía
                referente a superficies
                calientes
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Realizar pausas activas y descansos durante la jornada
                laboral, ropa de trabajo con mangaslargas (overol)
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Incendio y/o explosión
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Tecnológico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa de Mantenimiento
                preventivo y correctivo e
                inspecciones preoperacionales de
                equipos, maquinarias y vehículos.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantener al día la revisión
                de gases de los vehículos,
                Programa Manejo
                Defensivo yRiesgo
                Mecánico
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación en manejo de extintores, uso de trajes de
                contraincendios.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Trabajo bajo condiciones
                climáticasadversas (Lluvias,
                Tormentas Eléctricas)
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>FENÓMENO
                    NATURAL:</b>
                Lluvias
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Ninguno
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Disposición de detector de
                tormentas en los equipos
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Conformación y entrenamiento a lasbrigadas de
                emergencias. Dotación de invierno, divulgación del
                instructivo de tormentas.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Virus, bacterias,animales
                (semovientes).
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>Biológico</b>
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Inspeccionar el área de trabajo.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desinfección de
                herramientasy equipos con
                solución de amonio
                cuaternario o hipoclorito.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Lavado de manos, informar al HSEy activar plan de
                contingencia.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Trabajo en Alturas .
                Instalación y desinstalación
                deparilla de trabajo .
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Trabajoen Alturas
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Instalación de sistema de
                protección contra caídas y
                equipos de rescate
                inspeccionados y certificados
                acordes a la necesidadde la
                operación (Línea de vida vertical
                fija, retráctil 50 ft). Sistemas
                colectivos para protección de
                caídas (barandas),
                inspeccionadosy certificados.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Infografía referente al
                riesgopor trabajo en
                alturas, Señalización,
                inspección deelementos de
                alturas.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación y entrenamiento en TSA Avanzado,
                conformación de las brigadas de emergencia,
                elementos deprotección contra caídas (Arnés de
                cuerpo completo, eslingas con absorbedor de caídas,
                casco tipo II, eslinga de posicionamiento, eslinga
                Rebar). Inspeccionados y certificados acordes a la
                Res. 1409/2012.
                Coordinadores de alturas.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Sistemas escualizables de
                barandas, winche, malacates.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>CONDICIONES DE
                    SEGURIDAD:</b>
                Mecánico
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Disposición de guardas de
                seguridad, Encerramientos.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Señalización de
                advertencia.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación Prevención Riesgo Mecánico; hacer
                socialización conlos trabajadores sobre los riesgos
                existentes, controles y cuidados aliniciar actividades en
                el área
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Manejo manual decargas
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>BIOMECÁNICO:</b>
                Movimientos
                repetitivos,
                manipulación manual
                de cargas.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Identificación de los elementos
                quepueden ser manipulados
                manualmente por una persona,
                pordos personales o las que
                requierenayuda mecánica.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Ninguna
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Implementación del SVE OSTEOMUSCULAR
                (Exámenes médicos ocupacionales, pausas
                activas, charlas sobre estilos de vida saludable)
                Programa Disciplina
                Operativa, fichas humanizadas.
            </td>
        </tr>
        <tr>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Exposición directa alínea de
                peligro
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                <b>BIOMECÁNICO:</b>
                Locativo
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Inspección de área, Instalar
                guardas y mamparas.
            </td>
            <td style="width: 110px;text-align: center; border: 1px solid black; vertical-align: middle">
                Señalización de puntos de
                agarre seguro,
                demarcación ydelimitación
                de áreas.
            </td>
            <td style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle">
                Retirar personal no autorizado delárea de trabajo.
            </td>
        </tr>
        </tbody>
    </table>
    <p>(Ver información completa en Panoramas de Factores de Riesgos).</p>
    <br>
    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>10. ASPECTOS E IMPACTOS AMBIENTALES</b></h5>
        </div>
    </div>
    <table style="font-size: 12px;width: 100%; border-collapse: collapse">
        <thead>
        <tr style="font-size: 12px; text-align: center;">
            <th style="width: 120px;border: 1px solid black;text-align: center;padding: 5px;vertical-align: middle">
                DESCRIPCIÓN DE
                LA ACTIVIDAD
            </th>
            <th colspan="2"
                style="width: 210px;border: 1px solid black;text-align: center;padding: 5px;vertical-align: middle">
                DESCRIPCIÓN DE ASPECTOS E IMPACTOS AMBIENTALES
            </th>
            <th rowspan="2"
                style="width: 120px;border: 1px solid black;text-align: center;padding: 5px;vertical-align: middle">
                CONTROLES DE
                INGENERIAS ,
                ADVERTENCIA
                (Rediseñar, Aislar,
                separar),
                Programas
            </th>
            <th rowspan="2"
                style="width: 160px;border: 1px solid black;text-align: center;padding: 5px;vertical-align: middle">
                CONTROLES ADMINISTRATIVOS
                PRECAUCIONES Y/O SEÑALIZACION/ ADVERTENCIAS capacitaciones
            </th>
        </tr>
        <tr style="font-size: 12px; text-align: center">
            <th style="width: 120px; border: 1px solid black;padding: 5px;vertical-align: middle">
                ACTIVIDAD O SITUACIÓN
            </th>
            <th style="width: 90px; border: 1px solid black;padding: 5px;vertical-align: middle">
                DESCRIPCIÓN DE
                ASPECTO
            </th>
            <th style="width: 120px; border: 1px solid black;padding: 5px;vertical-align: middle">
                IMPACTO AMBIENTAL
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Iluminación de áreas
                operativas
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Consumo de
                energía eléctrica
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Disminución de los recursos Naturales.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa uso
                racional de agua y
                energía - Plan de
                manejo de agua y
                energía
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Señalización, Uso de energía eléctrica
                solo cuando sea necesario, Capacitación
                sobre uso racional de la energía
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento en
                áreas de iluminación
                en la cabina de la
                Unidad RSU
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos al
                romperse las
                bombillas
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del aire (Mercurio) y
                contaminación del suelo (Demás partes de
                la luminaria)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">

            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control manejo de residuos, Disposición
                con empresa de residuos o con el cliente,
                Capacitación sobre manejo de residuos
            </td>
        </tr>
        <tr>
            <!--<td  style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento en
                áreas de iluminación
                en la cabina de la
                Unidad RSU
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos peligrosos
                (Bombillos)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control manejo de residuos, Disposición
                con empresa de residuos o con el cliente,
                Capacitación sobre manejo de residuos
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso celular y Avantel
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos (Batería de
                celulares)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, residuos
                peligrosos; disminución de la biodiversidad
                ambiental, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Cuando los celulares terminan la vida útil
                son llevados a empresas de celulares
            </td>
        </tr>
        <tr>
            <!--<td  style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento en
                áreas de iluminación
                en la cabina de la
                Unidad RSU
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos Aparato
                celular o Avantel
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo - generación de
                residuos Eléctricos y Electrónicos,
                disminución de la biodiversidad ambiental,
                cambio climático, gases efecto invernadero.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Cuando los celulares terminan la vida útil
                son llevados a empresas de celulares
            </td>
        </tr>
        <tr>
            <td rowspan="4" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso de unidades
                sanitarias
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                vertimientos
                domésticos
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del agua; Disminución de la
                calidad de fuentes hídricas; disminución de
                la biodiversidad ambiental, gases efecto
                invernadero, calentamiento global
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Inspecciones
                ambientales
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control gestión de residuos, Correcta
                disposición en cestas de papel higiénico
            </td>
        </tr>
        <tr>
            <!--<td rowspan="4" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso de unidades
                sanitarias
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos - Químico
                usado para
                mantenimiento de
                los baños
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Manejo del residuo
                de los sanitarios
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Disposición con empresa especializada
                en manejo de residuos
            </td>
        </tr>
        <tr>
            <!--<td rowspan="4" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso de unidades
                sanitarias
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos sanitarios
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo y correcta
                disposición de residuos, clasificación de
                residuos en cesta de papel higiénico
            </td>
        </tr>
        <tr>
            <!--<td rowspan="4" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Uso de unidades
                sanitarias
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Consumo de agua
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Agotamiento de recursos hídricos,
                Contaminación del agua, disminución de la
                biodiversidad ambiental, cambio climático,
                gases efecto invernadero, calentamiento
                global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Inspecciones
                ambientales
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Refrigerios del
                personal (Mecatos,
                meriendas)
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos (Bolsas
                plásticas, de papel y
                servilletas)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación visual y del suelo,
                generación de residuos, disminución de la
                biodiversidad ambiental, cambio climático,
                gases efecto invernadero, calentamiento
                global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                No se permite el uso de desechables,
                Las bebidas se manejan en botellas
                retornables
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Servicio de Botiquín
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos
                hospitalarios
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Control manejo de residuos, Disposición
                con empresa de residuos o con el cliente,
                Capacitación sobre manejo de residuos
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Sismo y colapso
                estructural
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desastres naturales
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del aire, afectación en la
                salud del trabajador, aumento de la cantidad
                de residuos; disminución de la biodiversidad
                ambiental, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa de
                Simulacros
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantener establecidas y capacitadas las
                brigadas de emergencias
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Hidratación
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos (Bolsas
                plásticas)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Calentamiento global, contaminación visual
                y del suelo, generación de residuos,
                disminución de la biodiversidad ambiental,
                cambio climático, gases efecto invernadero.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Control gestión de residuos, Rec
            </td>
        </tr>
        <tr>
            <!--<td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Hidratación
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Consumo de agua
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Calentamiento global, agotamiento de
                recursos hídricos, disminución de la
                biodiversidad, extinción de especies, cambio
                climático, gases efecto invernadero.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Prog. uso racional
                de agua y energía -
                Plan de manejo de
                agua y energía
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre uso racional de la
                energía
            </td>
        </tr>
        <tr>
            <td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento de
                equipos, vehículos y
                maquinaria
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Consumo de aceites
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento
                preventivo y
                correctivo a
                equipos, vehículos
                y maquinaria
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Realización del cambio de aceites por
                personal competente, entregar residuos
                al cliente y/o empresa especializada
            </td>
        </tr>
        <tr>
            <!--<td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento de
                equipos, vehículos y
                maquinaria
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos (Baterías
                de vehículos)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Control manejo de residuos, Entrega de
                baterías a empresa de residuos o al
                cliente
            </td>
        </tr>
        <tr>
            <!--<td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento de
                equipos, vehículos y
                maquinaria
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación residuos
                contaminados por
                uso de aceites y
                grasa (Trapos,
                guantes, etc.)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos,
                Programa gestión de residuos, Entrega al
                cliente y/o empresa especializada
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Toma de fotos
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos (Baterías y
                cámaras)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, cambio climático,
                gases efecto invernadero.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de residuos
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Incendio y/o
                Explosión
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Conato de incendio
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Afecta la biodiversidad ambiental, el
                ecosistema en todos sus componentes
                (Suelo, Agua, atmosfera y biodiversidad).
                Altera el hábitat de las especies
                produciendo su extinción; se pierden nuevos
                recursos alimenticios, recursos forestales y
                otras materias primas; Gases efecto
                invernadero, Calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Plan de
                emergencias y
                contingencias,
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre manejo de extintores,
                Medevac actualizado y publicado,
                Brigadas Contraincendio,
            </td>
        </tr>
        <tr>
            <!--<td rowspan="2" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Incendio y/o
                Explosión
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, residuos
                contaminados, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Los residuos de químicos de extintores
                usados, serán clasificados como residuos
                contaminados y se dispondrán con
                empresa especializada en manejo de
                residuos
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Alimentación
                (Desayuno, Almuerzo
                y cena)
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Desarrollo
                programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                A cada trabajador se le entregaron
                cubiertos para, que NO utilicen
                desechables
            </td>
        </tr>
        <tr>
            <td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación residuos
                peligrosos o
                especiales (Aceites
                contaminados)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del suelo, disminución de la
                biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa gestión
                de residuos y de
                Mantenimiento e
                Inspección
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Documentos soporte del programa de
                Mantenimiento e Inspección, entrega de
                residuos a empresa especializada
            </td>
        </tr>
        <tr>
            <!--<td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos peligrosos
                o especiales
                (Llantas)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                sobrepresión del relleno sanitario
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa de Mnto
                e Inspección -
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Documentos soporte del programa de
                Mantenimiento e Inspección, entrega de
                residuos a trabajadores para reutilización
            </td>
        </tr>
        <tr>
            <!--<td rowspan="3" style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Mantenimiento
            </td>-->
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos peligrosos
                o especiales
                (Chatarra, filtros)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                sobrepresión del relleno sanitario
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa Mnto e
                Inspección -
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Documentos soporte del programa de
                Mantenimiento e Inspección, entrega de
                residuos a empresa especializada
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Generación de
                residuos peligrosos
                o especiales
                (Baterías)
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                sobrepresión del relleno sanitario
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa Mnto e
                Inspección -
                Programa gestión
                de residuos
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Documentos soporte del programa de
                Mantenimiento e Inspección, entrega de
                residuos a empresa especializada o
                como parte de pago de las nuevas
            </td>
        </tr>
        <tr>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Conducción de
                camionetas
            </td>
            <td style="width: 90px;text-align: center; border: 1px solid black; vertical-align: middle">
                Consumo de
                combustibles
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Contaminación del ambiente, disminución
                de la biodiversidad ambiental, extinción de
                especies, cambio climático, gases efecto
                invernadero, calentamiento global.
            </td>
            <td style="width: 120px;text-align: center; border: 1px solid black; vertical-align: middle">
                Programa manejo
                de sustancias
                químicas y de
                Mantenimiento e
                Inspección
            </td>
            <td style="width: 160px;text-align: center; border: 1px solid black; vertical-align: middle">
                Capacitación sobre hojas seguridad,
                primeros auxilios y brigadas de
                emergencia
            </td>
        </tr>
        </tbody>
    </table>
    <div style="padding-left: 20px; padding-top: 35px;">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>11. NOTA DE CONFIDENCIALIDAD</b></h5>
            <div style="margin-bottom: 25px;width: 670px">
                <div><?php echo $procedure['confidentiality_note'] ?></div>
            </div>
        </div>
    </div>
    <div style="padding-left: 20px; padding-top: 35px; margin-bottom: 35px;">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>12. CONTROL DE CAMBIOS</b></h5>
        </div>
    </div>
    <table style="font-size: 12px;width: 100%; border-collapse: collapse">
        <thead>
        <tr>
            <th style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">VERSIÓN</th>
            <th style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">MOTIVO CAMBIO
            </th>
            <th style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">FECHA</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">
                <?php echo $procedure['version'] ?>
            </td>
            <td style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">
                <?php echo $procedure['change_reason'] ?>
            </td>
            <td style="width: 234px;text-align: center; border: 1px solid black; vertical-align: middle">
                <?php echo $procedure['date'] ?>
            </td>
        </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <table style="font-size: 12px;width: 100%; border-collapse: collapse">
        <tr>
            <th style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle; padding: 5px">
                <img src="img/firma1.png" style="max-width: 100%; height: auto;">
                <br>
                ELABORADO / MODIFICADO POR:
                <b>KERVIN FERNANDEZ</b>
                COORDINADOR DE MANTENIMIENTO
            </th>
            <th style="width: 170px;text-align: center; border: 1px solid black; vertical-align: middle; padding: 5px">
                <img src="img/firma2.png" style="max-width: 100%; height: auto;">
                <br>
                REVISADO ÁREA OPERACIONES:
                <b>DANIEL ALARCÓN</b>
                INGENIERO DE OPERACIONES
            </th>
            <th style="width: 145px;text-align: center; border: 1px solid black; vertical-align: middle; padding: 5px">
                <img src="img/firma3.png" style="max-width: 100%; height: auto;">
                <br>
                REVISADO ÁREA SSTAQ:
                <b>LIGIA MARINA RIVERA T.</b>
                COORDINADOR SSTAQ
            </th>
            <th style="width: 145px;text-align: center; border: 1px solid black; vertical-align: middle; padding: 5px">
                <img src="img/firma4.png" style="max-width: 100%; height: auto;">
                <br>
                APROBADO POR:
                <b>OLIVO FABRIS MARAZZA</b>
                GERENTE
            </th>
        </tr>
        <tr>
            <td colspan="4" style="border: 1px solid black; vertical-align: middle; padding: 5px">FECHA DE ELABORACIÓN Y/O MODIFICACIÓN: <?php echo $procedure['date'] ?></td>
        </tr>
    </table>
</page>
