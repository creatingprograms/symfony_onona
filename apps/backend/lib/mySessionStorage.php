<?php

/**
 * Adds option for turning off regenerate session id
 *
 * parameters: see sfSessionStorage
 *
 * @package symfony
 * @subpackage storage
 * @author Mathew Toth <developer@poetryleague.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author Sean Kerr <sean@code-box.org>
 * @author Sergei Miami <miami@blackcrystal.net>
 * @version SVN: $Id$
 */
class mySessionStorage extends sfPDOSessionStorage {

    public function initialize($options = array()) {
// add 'regenerate' option, that is true by default in symfony
        $options = array_merge(array(
            'regenerate' => true,
                ), $options);

// initialize the parent
        parent::initialize($options);
    }

    public function regenerate($destroy = false) {
        if ((bool) $this->options['regenerate'] === true) {
            return parent::regenerate($destroy);
        }
    }

    public function sessionRead($id) {
        // get table/columns
        $db_table = $this->options['db_table'];
        $db_data_col = $this->options['db_data_col'];
        $db_id_col = $this->options['db_id_col'];
        $db_time_col = $this->options['db_time_col'];

        try {
            $sql = 'SELECT ' . $db_data_col . ' FROM ' . $db_table . ' WHERE ' . $db_id_col . '=?';

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_STR, 255);

            $stmt->execute();
            // it is recommended to use fetchAll so that PDO can close the DB cursor
            // we anyway expect either no rows, or one row with one column. fetchColumn, seems to be buggy #4777
            $sessionRows = $stmt->fetchAll(PDO::FETCH_NUM);
            if (count($sessionRows) == 1) {
                return $sessionRows[0][0];
            } else {
                // session does not exist, create it
                $sql = 'INSERT INTO ' . $db_table . '(' . $db_id_col . ', ' . $db_data_col . ', ' . $db_time_col . ',ip,ip_update) VALUES (?, ?, ?, ?, ?)';

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_STR);
                $stmt->bindValue(2, '', PDO::PARAM_STR);
                $stmt->bindValue(3, time(), PDO::PARAM_INT);
                $stmt->bindValue(4, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $stmt->bindValue(5, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $stmt->execute();

                return '';
            }
        } catch (PDOException $e) {
            throw new sfDatabaseException(sprintf('PDOException was thrown when trying to manipulate session data. Message: %s', $e->getMessage()));
        }
    }

    public function sessionWrite($id, $data) {
        // get table/column
        $db_table = $this->options['db_table'];
        $db_data_col = $this->options['db_data_col'];
        $db_id_col = $this->options['db_id_col'];
        $db_time_col = $this->options['db_time_col'];
        $iprmaddr = $_SERVER["REMOTE_ADDR"];
        $sql = 'UPDATE ' . $db_table . ' SET ' . $db_data_col . ' = ?, ' . $db_time_col . ' = ' . time() . ',ip_update="' . $iprmaddr . '" WHERE ' . $db_id_col . '= ?';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $data, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new sfDatabaseException(sprintf('PDOException was thrown when trying to manipulate session data. Message: %s', $e->getMessage()));
        }

        return true;
    }

}