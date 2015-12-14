<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 02/09/2015
 * Time: 10:38
 */
namespace Library\Toolkit;
use  Stackable;
use  Worker;

class PollingHandler extends Stackable {


    public function __construct($args){
        $this->args = $args;
        $this->userId = $args['userId'];
        $this->timeStamp = isset($args['timeStamp']) ? 0 : $this->arg['timestamp'];
        $this->withFeeds = isset($args['withFeeds']) ? 0 : $this->arg['withFeeds'];
        $this->lastNotification = $args['lastNotification'];
        $this->lastFriendRequest = $args['lastFriendRequest'];
        $this->lastTalkMessage = $args['lastTalkMessage'];
    }

    public function run(){
        /* this particular object won't run */

        exit(json_encode($this->worker));
            $connection = $this->worker->get_connection();
            get_notification_count_user($connection);

    }

    public function  get_notification_count_user($connection)
    {
        try {

            $sql = "SELECT COUNT(1) qtd
                        FROM notification
                       WHERE to_user_id = :to_user_id";

            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':to_user_id', $this->userId, \PDO::PARAM_INT);
            $stmt->execute();
            $resultSet = $stmt->fetchAll(\PDO::FETCH_OBJ);


            exit(json_encode($resultSet[0]));
            if (count($resultSet) == 1) {
                return $resultSet[0]->qtd;
            }

            return 0;
        } catch (\PDOException $e) {
            throw e;
        }
    }

}