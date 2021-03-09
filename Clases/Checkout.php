<?php


namespace Clases;


class Checkout
{
    public function initial(string $type, string $user)
    {
        //status EMPTY.OPEN,CLOSED
        //type USER,GUEST,NEWER
        //stage-X -> status OPEN,CLOSED
        //stage-1 -> subtype HOME,SPECIAL,API

        $_SESSION['stages'] = array(
            "status" => 'OPEN',
            "type" => $type,
            "user_cod" => $user,
            "cod" => $_SESSION['cod_pedido'],
            "stage-1" => '',
            "stage-2" => '',
            "stage-3" => ''
        );
    }

    public function user(string $user, string $type)
    {
        if (!empty($_SESSION['stages'])) {
            $_SESSION['stages']['user_cod'] = $user;
            $_SESSION['stages']['type'] = $type;
            return true;
        } else {
            return false;
        }
    }

    public function stage1(array $data)
    {
        //stage-X -> status OPEN,CLOSED
        //stage-1 -> subtype HOME,SPECIAL,API
        if (is_array($data)) {
            $_SESSION['stages']['stage-1'] = array(
                "status" => "CLOSED",
                "type" => "SHIPPING",
                "subtype" => '',
                "cod" => $data['envio'],
                "data" => array(
                    "nombre" => $data['nombre'],
                    "apellido" => $data['apellido'],
                    "dni" => "",
                    "email" => $data['email'],
                    "calle" => $data['direccion'],
                    "numero" => "",
                    "piso" => "",
                    "otros" => "",
                    "pais" => "",
                    "provincia" => $data['provincia'],
                    "localidad" => $data['localidad'],
                    "postal" => "",
                    "telefono" => $data['telefono']
                ),
                "api_data" => array(
                    "tracking" => ''
                )
            );
            return true;
        } else {
            return false;
        }
    }

    public function stage2(array $data)
    {
        //stage-X -> status OPEN,CLOSED

        if (is_array($data)) {
            $_SESSION['stages']['stage-2'] = array(
                "status" => "CLOSED",
                "type" => "BILLING",
                "data" => array(
                    "nombre" => $data['nombre'],
                    "apellido" => $data['apellido'],
                    "dni" => $data['dni'],
                    "email" => $data['email'],
                    "calle" => $data['direccion'],
                    "numero" => "",
                    "piso" => "",
                    "otros" => "",
                    "pais" => "",
                    "provincia" => $data['provincia'],
                    "localidad" => $data['localidad'],
                    "postal" => "",
                    "telefono" => $data['telefono'],
                    "factura" => $data['factura']
                )
            );
            return true;
        } else {
            return false;
        }
    }

    public function stage3(string $subtype, array $data)
    {
        //stage-X -> status OPEN,CLOSED
        //subtype NORMAL,API

        if (is_array($data)) {
            $_SESSION['stages']['stage-3'] = array(
                "status" => "CLOSED",
                "type" => "PAYMENT",
                "subtype" => '',
                "cod" => $data['cod'],
                "api_data" => array()
            );

            return true;
        } else {
            return false;
        }
    }

    public function progress()
    {
        if ($_SESSION['stages']['status'] == 'OPEN') {

            if (empty($_SESSION['stages']['stage-1'])) {
                $shipping = '';
            } else {
                $shipping = true;
            }

            if (empty($_SESSION['stages']['stage-2'])) {
                $billing = '';
            } else {
                $billing = true;
            }

            if (empty($_SESSION['stages']['stage-3'])) {
                $payment = '';
            } else {
                $payment = true;

            }

            $response = array(
                "stage-1" => $shipping,
                "stage-2" => $billing,
                "stage-3" => $payment,
                "finished" => false
            );

            return $response;
        } else {
            if ($_SESSION['stages']['status'] == 'CLOSED') {
                $response = array(
                    "stage-1" => true,
                    "stage-2" => true,
                    "stage-3" => true,
                    "finished" => true
                );
                return $response;
            } else {
                $response = array(
                    "stage-1" => '',
                    "stage-2" => '',
                    "stage-3" => '',
                    "finished" => false
                );
                return $response;
            }
        }
    }

    public function destroy()
    {
        if (isset($_SESSION['stages'])) {
            unset($_SESSION['stages']);
        }
    }

    public function close()
    {
        if (isset($_SESSION['stages'])) {
            $_SESSION['stages']['status'] = 'CLOSED';
        }
    }
}