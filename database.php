<?php

class Database
{
  /**
   * Storage
   *
   * @var array
   */
  protected $database = array();

  /**
   * Transactions
   *
   * @var array
   */
  protected $transactions = array();

  /**
   * Value usage counter
   *
   * @var array
   */
  protected $valueUsage = array();

  /**
   * Set value
   *
   * @param $name
   * @param bool $value
   */
  protected function setValue($name,$value=false)
  {
    if(isset($this->database[$name])) {
      $this->valueUsage[$this->database[$name]] -= 1;
      unset($this->database[$name]);
    }

    if ($value) {
      $this->database[$name] = $value;
      // IDEA: if values are mostly large, we can keep their hashes as $valueUsage key
      $this->valueUsage[$value] = isset($this->valueUsage[$value]) ? $this->valueUsage[$value] + 1 : 1;
    }
  }

  /**
   * Keep records about value change
   *
   * @param $name
   */
  protected function addTransactionChange($name)
  {
    if ($this->transactions) {
      $lastTransaction = end($this->transactions);
      if (!isset($lastTransaction[$name])) {
        $this->transactions[key($this->transactions)][$name] = $this->get($name);
      }
    }
  }

  /**
   * Open a transactional block
   */
  public function begin()
  {
    array_push($this->transactions, array());
  }

  /**
   * Rollback all of the commands from the most recent transaction block
   *
   * @throws Exception
   */
  public function rollback()
  {
    if ($this->transactions) {
      $lastTransaction = end($this->transactions);
      foreach($lastTransaction as $name => $value) {
        $this->setValue($name,$value);
      }
      unset($this->transactions[key($this->transactions)]);
    } else {
      throw new Exception("INVALID ROLLBACK");
    }
  }

  /**
   * Permanently store all of the operations from any presently open transactional blocks
   */
  public function commit()
  {
    $this->transactions = array();
  }

  /**
   * Return the value stored under the variable $name
   *
   * @param $name
   * @return string
   */
  public function get($name)
  {
    return isset($this->database[$name]) ? $this->database[$name] : 'null';
  }

  /**
   * Set variable
   *
   * @param $name
   * @param $value
   */
  public function set($name,$value)
  {
    $this->addTransactionChange($name);
    $this->setValue($name,$value);
  }

  /**
   * Unset variable
   *
   * @param $name
   */
  public function delete($name)
  {
    $this->addTransactionChange($name);
    $this->setValue($name);
  }

  /**
   * Returns the number of variables equal to $value
   *
   * @param $value
   * @return int
   */
  public function numEqualTo($value)
  {
    return isset($this->valueUsage[$value]) ? $this->valueUsage[$value] : 0;
  }
}