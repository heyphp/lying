<?php
namespace lying\db;

use lying\service\Service;

class Connection extends Service
{
    /**
     * @var string 数据源
     * @see http://php.net/manual/en/pdo.construct.php
     */
    protected $dsn;
    
    /**
     * @var string 数据库账号
     */
    protected $user;
    
    /**
     * @var string 数据库密码
     */
    protected $pass;
    
    /**
     * @var \PDO PDO实例
     */
    private $dbh;
    
    /**
     * 获取数据库实例
     * @return \PDO
     */
    public function pdo()
    {
        if (!($this->dbh instanceof \PDO)) {
            $this->dbh = new \PDO($this->dsn, $this->user, $this->pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }
        return $this->dbh;
    }
    
    /**
     * 预处理sql语句
     * @param string $statement 准备要执行的语句
     * @return \PDOStatement
     */
    public function prepare($statement)
    {
        return $this->pdo()->prepare($statement);
    }
    
    /**
     * 创建查询构造器
     * @return Query
     */
    public function createQuery()
    {
        return new Query($this);
    }
    
    /**
     * 启动一个事务
     * @return boolean 成功时返回true,或者在失败时返回false
     */
    public function beginTransaction()
    {
        return $this->pdo()->beginTransaction();
    }
    
    /**
     * 提交一个事务
     * @return boolean 成功时返回true,或者在失败时返回false
     */
    public function commit()
    {
        return $this->pdo()->commit();
    }
    
    /**
     * 回滚一个事务
     * @return boolean 成功时返回true,或者在失败时返回false
     */
    public function rollBack()
    {
        return $this->pdo()->rollBack();
    }
}