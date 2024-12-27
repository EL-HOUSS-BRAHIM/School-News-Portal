<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected $table = 'users';

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        return parent::find($this->table, $id);
    }

    public function save($data)
    {
        return parent::save($this->table, $data);
    }

    public function update($id, $data)
    {
        $columns = implode(" = ?, ", array_keys($data)) . " = ?";
        $values = array_values($data);
        $values[] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $columns WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete($id)
    {
        return parent::delete($this->table, $id);
    }

    public function findByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function authenticate($username, $password)
    {
        $user = $this->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function manageRoles($id, $role)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }
}
?>