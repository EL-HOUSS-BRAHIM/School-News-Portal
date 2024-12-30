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
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log("Error finding user: " . $e->getMessage());
        return null;
    }
}

    public function save($data)
    {
        return parent::save($data);
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

    public function findByUsername($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, password, role, created_at FROM users WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding user: " . $e->getMessage());
            return null;
        }
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