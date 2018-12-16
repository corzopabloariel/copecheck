
<?php
//81dc9bdb52d04dc20036dbd8313ed055
/*
 * DECLARACION DE ENTIDADES PARA EL SISTEMA
 */

/**
 * Contiene las entidades y objetos que el sistema manejara.
 * El usuario debe modificar estas entidades a gusto,
 * observando y tomando las precauciones necesarias para no
 * cometer errores y ambiguedades.
 *
 * Formato:
 *
 * <code>
 * [nombre_entidad => [atributos => [artributo => [TIPOS y
 * PROPIEDADES, n atributos],relacion_externa => [TIPOS Y
 * PROPIEDADES, n relaciones],n config varias => valor]
 * </code>
 *
 * Consulte /sys/definitions.php o tipee TP_ para conocer las
 * distintas propiedades y tipos que puede tomar cada atributo
 */

define( 'PYRUS_ENTIDADES', [
    'cheque' => [ // objeto o entidad
        'atributos' => [ // por cada atributo tengo que definir su comportamiento, necesito un diccionario de ellos
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'fecha_ingreso'     => [TP_FECHA_CORTA,TP_VISIBLE_NUNCA,'nombre_bonito' => 'fecha de ingreso'],
            'fecha_emision'     => [TP_FECHA_CORTA, 'nombre_bonito' => 'fecha de emisión'],
            'fecha_cobro'       => [TP_FECHA_CORTA, 'nombre_bonito' => 'fecha de cobro'],
            'n_serie'           => [TP_ENTERO, 'nombre_bonito' => 'n° serie'],
            'monto'             => [TP_FLOTANTE,
                                    'tipo' => 'moneda'],
            'id_moneda'         => [TP_RELACION,
                                    'relacion' => 'moneda',
                                    'formato_visible' => '[designacion]',
                                    'nombre_bonito' => 'moneda',
                                    'estado' => 'normal'],
            'id_banco'          => [TP_RELACION,
                                    'relacion' => 'banco',
                                    'formato_visible' => '[nombre] - [sucursal]',
                                    'nombre_bonito' => 'banco',// banco del cheque
                                    'estado' => 'agregar'],
            'id_librador'       => [TP_RELACION,// ----> el que expide y firma el cheque
                                    'relacion' => 'librador',//librador
                                    'formato_visible' => '[persona/id_persona/nombre_mostrar]',//razon_social
                                    'nombre_bonito' => 'librador',
                                    'estado' => 'normal'],
            'id_librado'        => [TP_RELACION, // ----> el que puede recibir el pago
                                    'relacion' => 'persona',//librado
                                    'formato_visible' => '[nombre_mostrar]',//[razon_social]
                                    'nombre_bonito' => 'librado',
                                    'estado' => 'agregar'],
            'id_portador'       => [TP_RELACION,// ----> el que tiene elcheque
                                    'relacion' => 'persona',//portador
                                    'formato_visible' => '[nombre_mostrar]',//[razon_social]
                                    'nombre_bonito' => 'cliente',
                                    'estado' => 'agregar'],
            'imagen'            => [TP_IMAGEN],
            'obs'               => [TP_STRING_LARGO,'nombre_bonito' => 'observaciones'],
            'espejo'            => [TP_ENTERO,TP_VISIBLE_NUNCA],
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]/*, 'visibilidad'    => "id - fecha / lugar - monto - imagen "*/
    ],
    'chequeaccion' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'id_cheque'         => [TP_RELACION,
                                    'relacion' => 'cheque',
                                    'formato_visible' => '[n_serie]',
                                    'nombre_bonito' => 'cheque',
                                    'estado' => 'normal'],
            'id_accion'         => [TP_RELACION,
                                    'relacion' => 'accion',
                                    'formato_visible' => '[designacion]',
                                    'nombre_bonito' => 'acción',
                                    'estado' => 'normal'],
            'i_cuenta_origen'   => [TP_RELACION,
                                    'relacion' => 'cuentaexterna',
                                    'formato_visible' => '[n_cuenta]',
                                    'nombre_bonito' => 'cuenta origen',
                                    'estado' => 'agregar'],
            'i_cuenta_destino'  => [TP_RELACION,
                                    'relacion' => 'cuenta',
                                    'formato_visible' => '[n_cuenta]',
                                    'nombre_bonito' => 'cuenta destino',
                                    'estado' => 'normal'],
            'e_cuenta_origen'   => [TP_RELACION,
                                    'relacion' => 'cuenta',
                                    'formato_visible' => '[n_cuenta]',
                                    'nombre_bonito' => 'cuenta origen',
                                    'estado' => 'normal'], // cuenta asociada
            'e_cuenta_destino'  => [TP_RELACION,
                                    'relacion' => 'cuentaexterna',
                                    'formato_visible' => '[n_cuenta]',
                                    'nombre_bonito' => 'cuenta destino',
                                    'estado' => 'agregar'], // cuenta destino
        ]
    ],
    /*
     * Todos los cheques que salen o ya no se pueden usar
     * A Depositar
     * Pasar (pago o envio dinero a persona)
     * Negociar
     */
    'chequefuera' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'id_cheque'         => [TP_ENTERO,
                                    'nombre_bonito' => 'id cheque'],
        ]
    ],
    'cuenta' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'n_cuenta'          => [TP_ENTERO,
                                    'nombre_bonito' => 'n° cuenta'],
            'cbu'               => [TP_ENTERO],
            'id_cooperativa'    => [TP_RELACION,
                                    'relacion' => 'cooperativa',
                                    'formato_visible' => '[razon_social]',
                                    'nombre_bonito' => 'cooperativa',
                                    'estado' => 'agregar'],
            'id_banco'          => [TP_RELACION,
                                    'relacion' => 'banco',
                                    'formato_visible' => '[nombre] - [sucursal]',
                                    'nombre_bonito' => 'banco',
                                    'estado' => 'agregar'],
            'id_moneda'         => [TP_RELACION,
                                    'relacion' => 'moneda',
                                    'formato_visible' => '[designacion]',
                                    'nombre_bonito' => 'moneda',
                                    'estado' => 'agregar'],
            'id_titular'        => [TP_RELACION,
                                    'relacion' => 'titular',
                                    'formato_visible' => "[persona/id_persona/nombre_mostrar]",
                                    'nombre_bonito' => 'titular',
                                    'estado' => 'agregar'],
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ],//cuenta cbu
    'cooperativa' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'razon_social'      => [TP_STRING,"nombre_bonito" => "razón social"],
        ]
    ],
    'banco' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'nombre'            => [TP_STRING],
            'sucursal'          => [TP_STRING],
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ],
    'sucursal' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'id_banco'          => [TP_RELACION,
                                    'relacion' => 'banco',
                                    'formato_visible' => '[nombre]'],
            'id_domicilio'      => [TP_RELACION,
                                    'relacion' => 'domicilio',
                                    'formato_visible' => '[calle] n [altura]'],
            'telefono'          => [TP_STRING,
                                    'nombre_bonito' => 'teléfono']
        ]
    ],
    'titular' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'id_persona'        => [TP_RELACION,
                                    'relacion' => 'persona',
                                    'formato_visible' => '[nombre_mostrar]',
                                    'nombre_bonito' => 'persona',
                                    'estado' => 'agregar'],
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ],
    'librador' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'id_persona'        => [TP_RELACION,
                                    'relacion' => 'persona',
                                    'formato_visible' => '[nombre_mostrar]',
                                    'nombre_bonito' => 'dato',
                                    'estado' => 'agregar'],
            'id_domicilio'      => [TP_RELACION,
                                    'relacion' => 'domicilio',
                                    'formato_visible' => '[calle] n [altura]',
                                    'nombre_bonito' => 'domicilio'],
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
            //'id_contacto'       => [TP_RELACION]
        ]
    ],
    'librado' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'razon_social'      => [TP_STRING],
            'cuit'              => [TP_ENTERO],
            'id_domicilio'      => [TP_RELACION],
            'id_contacto'       => [TP_RELACION]
        ]
    ],
    
    'moneda' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'designacion'       => [TP_STRING]
        ]
    ],
    'domicilio' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'calle'             => [TP_STRING],
            'altura'            => [TP_ENTERO],
            'localidad'         => [TP_STRING],
        ]
    ],
    'contacto' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'telefono'          => [TP_STRING],
            'email'             => [TP_STRING]
        ]
    ],

    'persona' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'nombre_mostrar'    => [TP_STRING,TP_VISIBLE_NUNCA],// nombre a mostrar del objeto, 
            'nombre'            => [TP_STRING],
            'apellido'          => [TP_STRING],
            'dni'               => [TP_ENTERO],
            'razon_social'      => [TP_STRING,'nombre_bonito' => 'razón social'],
            'cuit'              => [TP_ENTERO],
            'id_user'           => [TP_RELACION,TP_VISIBLE_NUNCA, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ],
    //
    //
    'usuarios' => [//
        'atributos' => [
            'id'            => [TP_PK, TP_VISIBLE_NUNCA],
            'user'          => [TP_STRING, TP_VISIBLE_SIEMPRE,
                                'nombre_bonito' => 'usuario'],
            'pass'          => [TP_PASSWORD, TP_INTRANSFERIBLE,
                                'nombre_bonito' => 'contraseña'],
            'nivel'         => [TP_ENTERO, TP_INTRANSFERIBLE],
        ]
    ],
    'portador' => [//
        'atributos' => [
            'id'            => [TP_PK,TP_VISIBLE_NUNCA],
            'persona'       => [TP_RELACION,
                                'relacion' => 'persona',
                                'formato_visible' => '[nombre_mostrar]',
                                'mostrar' => true]
        ]
    ],
    'movimiento' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'did'               => [TP_ENTERO,TP_VISIBLE_NUNCA],//id único de movimiento
            'detalle'           => [TP_STRING],
            'fecha'             => [TP_FECHA_CORTA,TP_VISIBLE_NUNCA],
            'id_cheque'         => [TP_RELACION,
                                    'relacion' => 'cheque',
                                    'formato_visible' => '[n_serie]',
                                    'nombre_bonito' => 'n° serie'],
                                    //'mostrar' => true],
            'id_portador'       => [TP_RELACION,
                                    'relacion' => 'persona',
                                    'formato_visible' => '[nombre_mostrar]',
                                    'nombre_bonito' => 'portador'],
                                    //'mostrar' => true],
            'id_destinatario'   => [TP_RELACION,
                                    'relacion' => 'persona',
                                    'formato_visible' => '[nombre_mostrar]',
                                    'nombre_bonito' => 'destinatario'],
                                    //'mostrar' => true],
            'accion'            => [TP_RELACION,
                                    'relacion' => 'accion',
                                    'formato_visible' => '[designacion]',
                                    'nombre_bonito' => 'acción',
                                    'estado' => 'normal'],// corresponde a accion efectuada en un cheque
            'estado'            => [TP_RELACION,
                                    'relacion' => 'movimientoestado',
                                    'formato_visible' => '[designacion]',
                                    'estado' => 'normal'],// 2 -> pendiente / 4 -> acreditado / 8 -> rebotado 
            'tipo_movimiento'   => [TP_RELACION,
                                    'relacion' => 'movimientotipo',
                                    'formato_visible' => '[designacion]',
                                    'nombre_bonito' => 'tipo de movimiento',
                                    'estado' => 'normal'],// 2 -> ingreso / 4 -> egreso / 6 -> espejo,
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ],
    'movimientoestado' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'designacion'       => [TP_STRING]
        ]
    ],
    'movimientotipo' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'designacion'       => [TP_STRING]
        ]
    ],
    'accion' => [
        'atributos' => [
            'id'            => [TP_PK,TP_VISIBLE_NUNCA],
            'designacion'   => [TP_STRING],
        ]
    ],
    'cuentaexterna' => [
        'atributos'     => [
            'id'            => [TP_PK,TP_VISIBLE_NUNCA],
            'n_cuenta'      => [TP_ENTERO, 'nombre_bonito' => 'n° cuenta'],
            'id_user'       => [TP_RELACION, // ----> el que genera el registro
                                'relacion' => 'usuarios',
                                'formato_visible' => '[user]',
                                'nombre_bonito' => 'usuario',
                                'estado' => 'uno']
        ]
    ],
    'espejo' => [
        'atributos' => [
            'id'                => [TP_PK,TP_VISIBLE_NUNCA],
            'did'               => [TP_ENTERO,TP_VISIBLE_NUNCA],//id único de movimiento
            'fecha'             => [TP_FECHA_CORTA,TP_VISIBLE_NUNCA],
            'id_cooperativa'    => [TP_RELACION,
                                    'relacion' => 'cooperativa',
                                    'formato_visible' => '[razon_social]',
                                    'nombre_bonito' => 'cooperativa'],
            'did_movimiento'    => [TP_ENTERO],// Valor que se repite
            'id_movimiento'     => [TP_RELACION,
                                    'relacion' => 'movimiento',
                                    'formato_visible' => '[did]',
                                    'nombre_bonito' => 'movimiento'],
                                    //'mostrar' => true],
            'id_cliente'        => [TP_RELACION,
                                    'relacion' => 'persona',
                                    'formato_visible' => '[nombre_mostrar]',
                                    'nombre_bonito' => 'cliente'],
                                    //'mostrar' => true],
            'id_cheque'         => [TP_RELACION,
                                    'relacion' => 'cheque',
                                    'formato_visible' => '[n_serie]',
                                    'nombre_bonito' => 'cheque'],
                                    //'mostrar' => true],
            'bloque'            => [TP_ENTERO],//2-->BLOQUE A/4-->BLOQUE B,
            'id_user'           => [TP_RELACION, // ----> el que genera el registro
                                    'relacion' => 'usuarios',
                                    'formato_visible' => '[user]',
                                    'nombre_bonito' => 'usuario',
                                    'estado' => 'uno']
        ]
    ]
]);