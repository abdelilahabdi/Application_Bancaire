<?php
require_once __DIR__ . '/../config/Database.php';

abstract class BaseModel
{
    protected $id = null;
    protected static $table = "";

    public function getId()
    {
        return $this->id;
    }

    
    abstract public function toArray(): array;

    
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function save(): void
    {
        $pdo = Database::getConnection();
        $data = $this->toArray();

        if ($this->id === null) {
            // INSERT
            $cols = implode(", ", array_keys($data));
            $params = ":" . implode(", :", array_keys($data));

            $sql = "INSERT INTO " . static::$table . " ($cols) VALUES ($params)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);

            $this->id = (int)$pdo->lastInsertId();
        } else {
            // UPDATE
            $set = [];
            foreach (array_keys($data) as $col) {
                $set[] = "$col = :$col";
            }
            $setSql = implode(", ", $set);

            $sql = "UPDATE " . static::$table . " SET $setSql WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $data["id"] = $this->id;
            $stmt->execute($data);
        }
    }

    public function delete(): void
    {
        if ($this->id === null) return;

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        $stmt->execute(["id" => $this->id]);

        $this->id = null;
    }

    public static function find($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $obj = new static(); 
        $obj->fill($row);
        return $obj;
    }

    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM " . static::$table);
        $rows = $stmt->fetchAll();

        $items = [];
        foreach ($rows as $row) {
            $obj = new static();
            $obj->fill($row);
            $items[] = $obj;
        }
        return $items;
    }
}
