<?php

namespace Burgers;

require_once "DataBase.php";
require_once "./config.php";

class Order
{
    private $name;
    private $phone;
    private $email;
    private $street;
    private $home;
    private $part;
    private $appt;
    private $floor;
    private $comment;
    private $payment;
    private $callback;
    private $address = '';

    private $errorDataFromForms = 0;
    private $messgeErrorDataFromForms;

    private $db;

    public function __construct(array $dataFromForm)
    {
        $this->name = $dataFromForm['name'];
        $this->phone = $dataFromForm['phone'];
        $this->email = $dataFromForm['email'];
        $this->street = $dataFromForm['street'];
        $this->home = $dataFromForm['home'];
        $this->part = $dataFromForm['part'];
        $this->appt = (int)$dataFromForm['appt'];
        $this->floor = (int)$dataFromForm['floor'];
        $this->comment = $dataFromForm['comment'];
        $this->payment = $_REQUEST['payment'];
        $this->callback = @$_REQUEST['callback'];

        $this->validationInputData();
        $this->db = new DataBase(DB_HOSTNAME, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    }

    private function validationInputData()
    {
        $this->messgeErrorDataFromForms = "Для оформления заказа вам необходимо заполнить поля: <br />";

        if (empty($this->name)) {
            $this->messgeErrorDataFromForms .= "Имя<br />";
            $this->errorDataFromForms++;
        }
        if (empty($this->phone)) {
            $this->messgeErrorDataFromForms .= "Телефон<br />";
            $this->errorDataFromForms++;
        } else {
            $this->phone = str_replace([' ', '(', ')', '+'], '', $this->phone);
        }
        if (empty($this->email)) {
            $this->messgeErrorDataFromForms .= "email<br />";
            $this->errorDataFromForms++;
        } else {
            if (!preg_match("#([0-9a-z\.]+@[0-9a-z]+\.[a-z]+)#i", $this->email)) {
                $this->messgeErrorDataFromForms .= "введене не коректный email адрес<br />";
                $this->errorDataFromForms++;
            }
        }
        if (empty($this->street)) {
            $this->messgeErrorDataFromForms .= "Улица<br />";
            $this->errorDataFromForms++;
        } else {
            $this->address .= $this->street;
        }
        if (empty($this->home)) {
            $this->messgeErrorDataFromForms .= "Дом<br />";
            $this->errorDataFromForms++;
        } else {
            $this->address .= ', д. ' . $this->home;
        }
        if (!empty($this->part)) {
            $this->address .= ', корп. ' . $this->part;
        }
        if (!empty($this->appt)) {
            $this->address .= ', кв. ' . $this->appt;
        }
        if (empty($this->floor)) {
            $this->messgeErrorDataFromForms .= "Этаж<br />";
            $this->errorDataFromForms++;
        } else {
            $this->address .= ', этаж ' . $this->floor;
        }
        if (empty($this->payment)) {
            $this->messgeErrorDataFromForms .= "Выберите тип оплаты<br />";
            $this->errorDataFromForms++;
        }
        if (empty($this->callback)) {
            $this->callback = 1;
        } else {
            $this->callback = 0;
        }
        $this->messgeErrorDataFromForms .= "<br />";
    }

    public function getError(): int
    {
        return $this->errorDataFromForms;
    }

    public function getMessageError()
    {
        if ($this->errorDataFromForms) {
            return $this->messgeErrorDataFromForms;
        } else {
            return null;
        }
    }

    public function create(): string
    {
        $emailUserId = $this->getIdEmail();
        $query = "INSERT INTO order_total (`date_time`, `id_user`, `id_payment`, `comment`, `callback`, `address`) 
            VALUES(:datetime, :id_user, :id_payment, :comment, :callback, :address)";
        $params = [
            'datetime' => date("Y-m-d H:i:s"),
            'id_user' => $emailUserId,
            'id_payment' => $this->payment,
            'comment' => $this->comment,
            'callback' => $this->callback,
            'address' => $this->address
        ];
        $insertOrder = $this->db->fetchAll($query, $params);
        if (!is_null($insertOrder)) {
            return $this->getReport($emailUserId, $this->db->lastInsertId());
        }
        return "Не удалось оформить заказ, попробуйте позже<br />";
    }

    private function getReport(int $emailUserId, int $idOrder): string
    {
        $countOrders = $this->getCountOrdersFromIdEmail($emailUserId);
        return "Спасибо, ваш заказ будет доставлен по адресу: " . $this->address . "<br />" .
            "Номер вашего заказа: #{$idOrder} <br />" .
            "Это ваш {$countOrders}-й заказ!<br />";
    }

    private function getIdEmail(): int
    {
        $idEmail = $this->db->fetchAll("SELECT id FROM users WHERE email = :email", ['email' => $this->email]);
        if (empty($idEmail[0]['id'])) {
            $this->db->fetchAll(
                "INSERT INTO users(`email`, `name`, `phone`) VALUES(:email, :name, :phone)",
                ['email' => $this->email, 'name' => $this->name, 'phone' => $this->phone]
            );
            $idEmail = $this->db->lastInsertId();
        } else {
            $idEmail = $idEmail[0]['id'];
        }
        return $idEmail;
    }

    private function getCountOrdersFromIdEmail(int $idEmail): int
    {
        $query = "SELECT COUNT(*) AS count_orders FROM order_total WHERE id_user = :id_user";
        $countOrder = $this->db->fetchAll($query, ['id_user' => $idEmail]);
        return $countOrder[0]['count_orders'];
    }
}
