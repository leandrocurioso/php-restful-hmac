<?php

namespace Library\DataAccessObject;

use Library\DataAccessObject\DataAccessObject;
use Library\Toolkit\PDOHandler;
use Library\Toolkit\ArrayList;
use PDO;
use Library\ValueObject\ValueObject;
use Library\ValueObject\UserVO;
use Library\ValueObject\ApiProfileVO;
use PDOException;
use Exception;

class UserDAO extends DataAccessObject {

    public function __construct(PDOHandler $pdoHandler){
        try {
            parent::__construct($pdoHandler);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function create(ValueObject $objVO) {
        try {
            $query = " INSERT INTO user
                      (
                             name
                           , email
                           , password
                           , api_profile_id
                           , `active`
                      )
                      VALUES
                      (
                            :name
                          , :email
                          , :password
                          , :api_profile_id
                          , :active
                        )";

            $dataObject = null;
            if (is_object($objVO)) {
                $this->PDOHandler->query($query);
                $this->PDOHandler->bind(':name', $objVO->Name, PDO::PARAM_STR);
                $this->PDOHandler->bind(':email', $objVO->Email, PDO::PARAM_STR);
                $this->PDOHandler->bind(':password', $objVO->Password, PDO::PARAM_STR);
                $this->PDOHandler->bind(':api_profile_id', $objVO->ApiProfile->Id, PDO::PARAM_INT);
                $this->PDOHandler->bind(':active', $objVO->Active, PDO::PARAM_INT);
                $this->PDOHandler->execute();
                if ($this->PDOHandler->row_count() == 0) {
                    throw new Exception("Some error occured while trying to insert the User", 500);
                }
                return $this->PDOHandler->last_inserted_id();
            }
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function update(ValueObject $objVO) {
        try {
            $query = "UPDATE user SET ";

            if($objVO->Name){
              $query.= " name = :name ";
            }

            if($objVO->Email){
              $query.= " email = :email ";
            }

            if($objVO->Password){
              $query.= " password = :password ";
            }

            if(isset($objVO->ApiProfile) && $objVO->ApiProfile->Id){
              $query.= " api_profile_id = :api_profile_id ";
            }

            if($objVO->Active){
              $query.= " active = :active ";
            }

            $query.= " WHERE id = :id";

            $dataObject = null;
            if (is_object($objVO)) {

              $this->PDOHandler->query($query);

              if($objVO->Name){
                $this->PDOHandler->bind(':name', $objVO->Name, PDO::PARAM_STR);
              }

              if($objVO->Email){
                $this->PDOHandler->bind(':email', $objVO->Email, PDO::PARAM_STR);
              }

              if($objVO->Password){
                $this->PDOHandler->bind(':password', $objVO->Password, PDO::PARAM_STR);
              }

              if(isset($objVO->ApiProfile) && $objVO->ApiProfile->Id){
                $this->PDOHandler->bind(':api_profile_id', $objVO->ApiProfile->Id, PDO::PARAM_INT);
              }

              if($objVO->Active){
                $this->PDOHandler->bind(':active', $objVO->Active, PDO::PARAM_INT);
              }

              $this->PDOHandler->bind(':id', $objVO->Id, PDO::PARAM_INT);
              $this->PDOHandler->execute();
              return $this->PDOHandler->row_count() ? true : false;
            }
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function delete(ValueObject $objVO) {
        try {
            $query = " DELETE FROM user WHERE Id = :Id";
            $dataObject = null;
            if (is_object($objVO)) {
                $this->PDOHandler->query($query);
                $this->PDOHandler->bind(':Id', $objVO->Id, PDO::PARAM_INT);
                $this->PDOHandler->execute();
                if ($this->PDOHandler->row_count() == 0) {
                    throw new Exception("Some error occured while trying to delete the user", 500);
                } else {
                    return true;
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function read(ValueObject $objVO) {
        try {
          $query = "SELECT id
                         , name
                         , email
                         , password
                         , api_profile_id
                      FROM user
                      WHERE 1 = 1 ";

          if (is_object($objVO)) {

              if ($objVO->Id > 0) {
                  $query .= " AND id = :id";
              }

              if ($objVO->Name != null) {
                  $query .= " AND name = :name";
              }

              if ($objVO->Email != null) {
                  $query .= " AND email = :email";
              }

              if ($objVO->Password != null) {
                  $query .= " AND password = :password";
              }

              if (is_object($objVO->ApiProfile) && $objVO->ApiProfile->Id > 0) {
                  $query .= " AND user.api_profile_id = :api_profile_id";
              }
          }

          $this->PDOHandler->query($query . " ORDER BY user.id DESC LIMIT 1");

          if (is_object($objVO)) {

              if ($objVO->Id > 0) {
                  $this->PDOHandler->bind(':id', $objVO->Id, PDO::PARAM_INT);
              }

              if ($objVO->Name != null) {
                  $this->PDOHandler->bind(':name', $objVO->Name, PDO::PARAM_STR);
              }

              if ($objVO->Email != null) {
                  $this->PDOHandler->bind(':email', $objVO->Email, PDO::PARAM_STR);
              }

              if ($objVO->Password != null) {
                  $this->PDOHandler->bind(':password', $objVO->Password, PDO::PARAM_STR);
              }

              if (is_object($objVO->ApiProfile) && $objVO->ApiProfile->Id > 0) {
                  $this->PDOHandler->bind(':api_profile_id', $objVO->ApiProfile->Id, PDO::PARAM_INT);
              }
          }

          $resultSet = $this->PDOHandler->result_set();
          if (count($resultSet) > 0) {
              $objectVO = new UserVO();
              $objectVO->ApiProfile = new ApiProfileVO();
              $objectVO->Id = $resultSet[0]->id;
              $objectVO->Name = $resultSet[0]->name;
              $objectVO->Email = $resultSet[0]->email;
              $objectVO->Password = $resultSet[0]->password;
              $objectVO->ApiProfile->Id = $resultSet[0]->api_profile_id;
          }
          return $objectVO;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function list_all(ValueObject $objVO = null, $options = null) {
        try {
            $query = "SELECT id
                           , name
                           , email
                           , password
                           , api_profile_id
                        FROM user
                        WHERE 1 = 1 ";

            if (is_object($objVO)) {

                if ($objVO->Id > 0) {
                    $query .= " AND id = :id";
                }

                if ($objVO->Name != null) {
                    $query .= " AND name = :name";
                }

                if ($objVO->Email != null) {
                    $query .= " AND email = :email";
                }

                if ($objVO->Password != null) {
                    $query .= " AND password = :password";
                }

                if (is_object($objVO->ApiProfile) && $objVO->ApiProfile->Id > 0) {
                    $query .= " AND user.api_profile_id = :api_profile_id";
                }
            }

            $this->PDOHandler->query($query . " ORDER BY user.id DESC ");

            if (is_object($objVO)) {

                if ($objVO->Id > 0) {
                    $this->PDOHandler->bind(':id', $objVO->Id, PDO::PARAM_INT);
                }

                if ($objVO->Name != null) {
                    $this->PDOHandler->bind(':name', $objVO->Name, PDO::PARAM_STR);
                }

                if ($objVO->Email != null) {
                    $this->PDOHandler->bind(':email', $objVO->Email, PDO::PARAM_STR);
                }

                if ($objVO->Password != null) {
                    $this->PDOHandler->bind(':password', $objVO->Password, PDO::PARAM_STR);
                }

                if (is_object($objVO->ApiProfile) && $objVO->ApiProfile->Id > 0) {
                    $this->PDOHandler->bind(':api_profile_id', $objVO->ApiProfile->Id, PDO::PARAM_INT);
                }
            }

            $resultSet = $this->PDOHandler->result_set();
            $dataObject = null;
            if (count($resultSet) > 0) {
                $objectList = new ArrayList();
                foreach ($resultSet as $result) {
                    $objectVO = new UserVO();
                    $objectVO->ApiProfile = new ApiProfileVO();
                    $objectVO->Id = $result->id;
                    $objectVO->Name = $result->name;
                    $objectVO->Email = $result->email;
                    $objectVO->Password = $result->password;
                    $objectVO->ApiProfile->Id = $result->api_profile_id;
                    $objectList->add($objectVO, $objectVO->Id);
                }
                $dataObject = $objectList;
            }
            return $dataObject;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
