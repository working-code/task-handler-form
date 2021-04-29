<?php

namespace Burgers;

class DataBase
{
    private \PDO $db;
    private array $log = [];
    private int $errorCount = 0;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        try {
            $this->db = new \PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        } catch (PDOException $e) {
            $this->log[] = [
                'datetime' => date("d.m.Y H:i:s"),
                'message' => $e->getMessage()
            ];
            $this->errorCount++;
        }
    }

    public function getLog()
    {
        if ($this->errorCount) {
            return $this->log;
        }
        return null;
    }

    public function getErrorCount(): int
    {
        return $this->getErrorCount();
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function fetchAll(string $query, array $params)
    {
        $sth = $this->db->prepare($query);
        if ($sth->execute($params)) {
            return $sth->fetchAll($this->db::FETCH_ASSOC);
        }
        $this->log[] = [
            'datetime' => date("d.m.Y H:i:s"),
            'message' => $sth->errorCode() . ' ' . implode(', ', $sth->errorInfo()),
            'query' => $query,
            'params' => $params
        ];
        $this->errorCount++;
        return null;
    }
}
