<?php

require_once 'Model/API.php';
require_once 'DataBase.php';

class User extends API
{
    private $db;

    private $id;

    private $email;

    private $password;

    private $name;

    private $surname;

    private $telephoneNumber;

    const TABLE_NAME = 'user';

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = DataBase::getDB();
        $this->rules = [
            'create' => 'apiCreate',
            'edit' => 'apiEdit',
            'get' => 'apiGet',
            'login' => 'apiLogin'
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * @param mixed $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    public function edit($params) {
        $this->get($params['id']);

        if (!empty($params['name'])) {
            $this->setName($params['name']);
        }

        if (!empty($params['surname'])) {
            $this->setSurname($params['surname']);
        }

        if (!empty($params['telephone_number'])) {
            $this->setTelephoneNumber($params['telephone_number']);
        }

        if (!empty($params['new_password'])) {
            $this->setPassword($params['new_password']);
        }

        $this->save();
    }

    public function get($id) {
        $user = $this->db->selectRow(
            "SELECT * FROM " . self::TABLE_NAME . " WHERE id = " . DataBase::SYM_QUERY,
            [$id]
        );

        $this->setId($id);
        $this->setName($user['name']);
        $this->setSurname($user['surname']);
        $this->setEmail($user['email']);
        $this->setTelephoneNumber($user['telephone_number']);
        $this->setPassword($user['password']);

        return $user;
    }

    public function getByEmail($email) {
        $user = $this->db->selectRow(
            "SELECT * FROM " . self::TABLE_NAME . " WHERE email = " . DataBase::SYM_QUERY,
            [$email]
        );

        return $user;
    }

    public function save() {
        if (!$this->getId()) {
            return $this->db->query(
                "INSERT INTO " . self::TABLE_NAME . " (email, password, name, surname, telephone_number)
            VALUES (" . DataBase::SYM_QUERY . ", " . DataBase::SYM_QUERY . ", " . DataBase::SYM_QUERY . ", " . DataBase::SYM_QUERY . ", " . DataBase::SYM_QUERY . ")",
                [$this->email, $this->password, $this->name, $this->surname, $this->telephoneNumber]
            );
        } else {
            return $this->db->query(
                "UPDATE " . self::TABLE_NAME . " SET password = " . DataBase::SYM_QUERY . ", name = " . DataBase::SYM_QUERY . ", surname = " . DataBase::SYM_QUERY . ", telephone_number = " . DataBase::SYM_QUERY . " WHERE id = " . DataBase::SYM_QUERY,
                [$this->password, $this->name, $this->surname, $this->telephoneNumber, $this->id]
            );
        }
    }

    public function apiCreate($params) {
        $this->setName($params['name']);
        $this->setSurname($params['surname']);
        $this->setEmail($params['email']);
        $this->setTelephoneNumber($params['telephone_number']);
        $this->setPassword(password_hash($params['password'], PASSWORD_DEFAULT));

        $newUserId = $this->save();

        echo $newUserId;
    }

    public function apiGet($params) {
        $user = $this->get($params['id']);
        unset($user['password']);

        echo json_encode($user);
    }

    public function apiEdit($params) {
        $this->get($params['id']);

        if (!password_verify($params['current_password'], $this->getPassword())) {
            echo 'bad';

            return;
        }

        $this->edit($params);
    }

    public function apiLogin($params) {
        $user = $this->getByEmail($params['email']);
        if ($user && password_verify($params['password'], $user['password'])) {
            echo $user['id'];
        }
    }
}