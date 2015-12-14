<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 02/09/2015
 * Time: 10:01
 */
namespace Library\Toolkit;

use Handy\Application;
use  Thread;

class AsyncPolling extends Thread
{
    public function __construct($args)
    {
        $this->args = $args;
        $this->userId = $args['userId'];
        $this->timeStamp = isset($args['timeStamp']) ? 0 : $this->arg['timestamp'];
        $this->withFeeds = isset($args['withFeeds']) ? 0 : $this->arg['withFeeds'];
        $this->lastNotification = $args['lastNotification'];
        $this->serverRoot = $args['serverRoot'];
        $this->lastFriendRequest = $args['lastFriendRequest'];
        $this->lastTalkMessage = $args['lastTalkMessage'];

    }

    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    public function get_connect()
    {
        try {

            if (isset($this->args)) {
                $db = new \PDO(
                    "mysql:host={$this->args['sql_config']['host']};dbname={$this->args['sql_config']['db']}",
                    $this->args['sql_config']['user'],
                    $this->args['sql_config']['pass'],
                    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
                );
            }

        } catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. " .
                "The error reported by the server was: " . $e->getMessage()
            );
        }
        return $db;
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


            if (count($resultSet) == 1) {
                return $resultSet[0]->qtd;
            }

            return 0;
        } catch (\PDOException $e) {
            throw e;
        }
    }


    public function get_friend_request_count_user($connection)
    {

        try {

            $sql = "SELECT COUNT(1) qtd
                          FROM friend
                        WHERE user_friend_id = :user_friend_id
                          AND status = 1";

            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':user_friend_id', $this->userId, \PDO::PARAM_INT);
            $stmt->execute();
            $resultSet = $stmt->fetchAll(\PDO::FETCH_OBJ);

            if (count($resultSet) == 1) {
                return $resultSet[0]->qtd;
            }
            return 0;

        } catch (\PDOException $ex) {
            throw $ex;
        }
    }

    //Return array
    public function load_new_messages_form_talk_user($connection)
    {


        try {
       
            $sql = "SELECT
						  user.Id
						, message.text
						, user.picture_profile
						, user.first_name
						, user.last_name
						, message.add_date_time
						, message.Id AS MessageId
                        , talk.id AS TalkId
			        FROM user
			  INNER JOIN talk
					  ON (talk.from_user_id = user.id OR talk.to_user_id = user.id)
			  INNER JOIN message
					  ON message.talk_id = talk.id
					 AND message.user_sender_id = user.id
		           WHERE message.id > 0
                     AND talk_id IN (SELECT talk.id
                                       FROM talk
                                      WHERE talk.from_user_id = 1 OR talk.to_user_id = 1)";

            // $sql .= " AND message.user_sender_id <> :ignoreThisUserId ";

            $stmt = $connection->prepare($sql);
 
            // $stmt->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
            // $stmt->bindParam(':lastMessageId', $this->lastTalkMessage, \PDO::PARAM_INT);
            //$stmt->bindParam(':ignoreThisUserId', $this->userId, \PDO::PARAM_INT);

            $stmt->closeCursor();
            $stmt->execute();
            $dataObject = null;
            $lastMessage = 0;
            $talkIds = "";

            $objectList = [];
            $oldTalkId = 0;
            $toUserOk = false;
            $objectVO = null;

            $resultSet = $stmt->fetchAll(\PDO::FETCH_OBJ);
            foreach($resultSet as $result){
         
                $talkId = $result->TalkId;
                if ($oldTalkId != $talkId) {

                    $toUserOk = false;
                    $talkIds .= $talkId . ",";
                 
                    //Talk
                    $objectVO = [];

                    $objectVO['Id'] = $talkId;
                    $objectVO['FromUser']['Id'] = $this->userId;
                    $objectVO['Messages'] = [];

                }

                $vUserId = $result->Id;
                if (!$toUserOk && $this->userId != $vUserId) {
                    $toUserOk = true;
                    $objectVO['ToUser']['Id'] = $vUserId;
                    $objectVO['ToUser']['FirstName'] = $result->first_name;
                    $objectVO['ToUser']['LastName'] = $result->last_name;
                    $objectVO['ToUser']['PictureProfile'] = $result->picture_profile;
                }


                //Message
               // $messageVO = [];
                //User
               // $messageVO['UserSender'] = [];

                //Talk
              //  $messageVO['Talk'] = [];

                $messageVO['Id'] = $result->MessageId;
                $messageVO['Talk']['Id'] = $result->TalkId;
                $messageVO['Text'] = $result->text;
                $messageVO['AddDateTime'] = $result->add_date_time;
                $messageVO['UserSender']['Id'] = $result->Id;
                $messageVO['UserSender']['FirstName'] = $result->first_name;
                $messageVO['UserSender']['PictureProfile'] = $result->picture_profile;

                if ($messageVO['Id'] > $lastMessage) {
                    $lastMessage = $messageVO['Id'];
                }

                $messages[]  = $messageVO;
                echo "<pre>";

                var_dump($messages);
                 echo "</pre>";
             
               // $objectVO['Messages'][$messageVO['Id']] = $messageVO;

                if ($oldTalkId != $talkId) {
                    $oldTalkId = $talkId;
                    //$objectVO['Messages'] = $messages;
                    $objectList[$objectVO['Id']] = $objectVO;
                }
            }

              die;

            //exit(json_encode ($objectList[0]));
            $dataObject = $objectList;

            return array("talks" => $dataObject, "last_message" => $lastMessage, "talk_ids" => substr($talkIds, 0, strlen($talkIds) - 1));

        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public function run()
    {
   
        if (isset($this->args)) {

            try {
                $connection = self::get_connect();

                //self::get_notification_count_user($connection);
                self::load_new_messages_form_talk_user($connection);

                //$userBc = new $this->arg->get_userBC();
                /*
                     $data = [];
                     $userBc = new \Library\BusinessCore\UserBC();
                     $database = $userBc->Database;

                     $friendBc = new \Library\BusinessCore\FriendBC($database);
                     $notificationBc = new \Library\BusinessCore\NotificationBC($database);
                     $talkBc = new \Library\BusinessCore\TalkBC($database);

                     $userId = $this->arg['UserId'];
                     $timeStamp = $this->arg['timestamp'] == "" ? 0 : $this->arg['timestamp'];
                     $withFeeds = $this->arg['withFeeds'] == "" ? 0 : $this->arg['withFeeds'];
                     $lastNotification = $this->arg['lastNotifications'];
                     $lastFriendRequest = $this->arg['lastFiendRequest'];
                     $lastTalkMessage = $this->arg['lastTalkMessage'];

                     $count = 0;
                     $waitFeed = 0;
                     $waitTalkMessage = 0;
                     $waitUpdateState = 0;
                     $expirationLongLolling = Application::config("service->expiration_long_polling");

                     while (count($data) == 0 && $count <= (int)$expirationLongLolling) {

                         $notifications = $notificationBc->get_notification_count_user($userId);
                         $friendRequest = $friendBc->get_friend_request_count_user($userId);
                         $feeds = null;
                         $talkMessages = null;

                         if ($count == $waitTalkMessage + 5 || $count == 0) {
                             $waitTalkMessage = $count;

                             $talkMessages = $talkBc->load_new_messages_form_talk_user($userId, $lastTalkMessage, true);
                         }

                         if ($count == $waitUpdateState + 8 || $count == 0) {
                             $waitUpdateState = $count;
                             $userBc->update_state($userId);
                         }

                         if ($count == $waitFeed + 10 && $withFeeds == 1 || $count == 0 && $withFeeds == 1) {
                             $waitFeed = $count;
                             $feeds = $userBc->list_feeds($userId, 0, $timeStamp);
                         }

                         if (!empty($talkMessages["talks"]) && $talkMessages["talks"]->length() > 0) {
                             $talkBc->set_messages_talks_displayed($userId, $talkMessages["talk_ids"]);
                             $data["TALK_MESSAGES"] = $talkMessages["talks"]->get_items();
                             $data["LAST_TALK_MESSAGE"] = $talkMessages["last_message"];
                         }

                         if (!empty($feeds) && $feeds->length() > 0 && $withFeeds == 1) {
                             $firstFeed = $feeds->first();
                             $data["FEEDS"] = $feeds->get_items();
                             $data["FEEDS_TIMESTAMP"] = strtotime($firstFeed->PostDateTime . " EDT 2014");
                         }

                         if ($notifications > 0 && $lastNotification != $notifications) {
                             $data["NOTIFICATIONS"] = $notifications;
                         }

                         if ($friendRequest > 0 && $lastFriendRequest != $friendRequest) {
                             $data["FRIEND_REQUEST"] = $friendRequest;
                         }

                         sleep(1);
                         $count++;
                     }

                     $response["status"] = true;
                     $response["data"] = $data;
                     exit(json_encode($response));*/

            } catch (Exception $e) {
                throw $e;
            }

        }
    }
}