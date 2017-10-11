<?php
namespace PriWare\Model\Dao;

use Interop\Container\ContainerInterface;
use PriWare\Lib\Conditions;
use PriWare\Model\Entity\User;

class UserDao
{
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }
    
    public function authenticate(string $user_id, string $admin_pw)
    {
        $sql = "
            SELECT *
            FROM user
            WHERE id = :user_id AND password = PASSWORD(:user_pw)
            AND status = :status
        ";
        $stmt = $this->app->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, \PDO::PARAM_STR);
        $stmt->bindValue(':user_pw', $user_pw, \PDO::PARAM_STR);
        $stmt->bindValue(':status', User::STATUS_ACTIVE, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchObject(User::class);
        
        return $user;
    }
    
    public function createUser(User $user)
    {
        $sql = "
            INSERT INTO user
            SET
                id = :id,
                name = :name,
                password = PASSWORD(:password),
                type = :type,
                status = :status,
                regdate = :regdate
        ";
        $stmt = $this->app->db->prepare($sql);
        $result = $stmt->execute([
            ':id' => $user->id,
            ':name' => $user->name,
            ':password' => $user->password,
            ':type' => $user->type,
            ':status' => $user->status,
            ':regdate' => $user->regdate
        ]);
        
        return $result;
    }
}