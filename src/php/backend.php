<?php 
    require './database.php';

    class backend
    {
        public function login($username, $password)
        {
            return self::loginAccount($username, $password);
        }

        public function register($fullname, $username, $password)
        {
            return self::registerAccount($fullname, $username, $password);
        }

        public function displayAccount()
        {
            return self::displayAcc();
        }

        public function addEvents($eventname, $eventstart, $eventend)
        {
            return self::addEvent($eventname, $eventstart, $eventend);
        }

        public function displayEvents()
        {
            return self::displayEvent();
        }

        public function deleteEvent($event_id)
        {
            return self::deleteThisEvent($event_id);
        }

        public function changeUsername($newUsername)
        {
            return self::updateUsername($newUsername);
        }

        public function changePassword($newPassword)
        {
            return self::updatePassword($newPassword);
        }

        public function changeFullname($newFullname)
        {
            return self::updateFullname($newFullname);
        }
        
        private function updateUsername($newUsername)
        {
            try {
                if ($newUsername != '') {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::updateUsernameQuery());
                        $stmt->execute(array($newUsername, self::getDateToday(),self::getAccID()));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }
        
        private function updatePassword($newPassword)
        {
            try {
                if ($newPassword != '') {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::updatePasswordQuery());
                        $stmt->execute(array(md5($newPassword), self::getDateToday(),self::getAccID()));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function updateFullname($newFullname)
        {
            try {
                if ($newFullname != '') {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::updateFullnameQuery());
                        $stmt->execute(array($newFullname, self::getDateToday(),self::getAccID()));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function deleteThisEvent($event_id)
        {
            try {
                if (self::checkLogin($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::deleteEventQuery());
                        $stmt->execute(array(self::getAccID(), $event_id));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function displayEvent()
        {
            try {
                if (self::checkLogin($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::getAllEvents());
                        $stmt->execute(array(self::getAccID()));
                        $res = $stmt->fetchAll();

                        $db->closeConnection();
                        return json_encode($res);
                    } else {
                        return "403";
                    }
                } else {
                    return "403";
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function addEvent($eventname, $eventstart, $eventend)
        {
            try {
                if (self::checkAddEvent($eventname, $eventstart, $eventend)) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::insertEventsQuery());
                        $stmt->execute(array(self::getAccID(), $eventname, $eventstart, $eventend));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function displayAcc()
        {
            try {
                if (self::checkLogin($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::getAccountQuery());
                        $stmt->execute(array(self::getAccID()));
                        $res = $stmt->fetchAll();

                        $db->closeConnection();
                        return json_encode($res);
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function registerAccount($fullname, $username, $password)
        {
            try {
                if (self::checkRegister($fullname, $username, $password)) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::registerQuery());
                        $stmt->execute(array($fullname, $username, md5($password), self::getDateToday(), self::getDateToday()));
                        $res = $stmt->fetch();
                        if (!$res) {
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }   
        }

        private function loginAccount($username, $password)
        {
            try {
                if (self::checkLogin($username, $password)) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::loginQuery());
                        $stmt->execute(array($username, md5($password)));
                        $res = $stmt->fetch();
                        if ($res) {
                            $_SESSION["username"] = $username;
                            $_SESSION["password"] = md5($password);
                            $db->closeConnection();
                            return '200';
                        } else {
                            $db->closeConnection();
                            return '404';
                        }
                    } else {
                        return '403';
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function getAccID()
        {
            try {
                if (self::checkLogin($_SESSION["username"], $_SESSION["password"])) {
                    $db = new database();
                    if ($db->getStatus()) {
                        $stmt = $db->getConnection()->prepare(self::loginQuery());
                        $stmt->execute(array($_SESSION["username"], $_SESSION["password"]));
                        $res = null;

                        while ($a = $stmt->fetch()) {
                            $res = $a['id'];
                        }
                        $db->closeConnection();
                        return $res;
                    } else {
                        $db->closeConnection();
                    }
                } else {
                    return '403';
                }
            } catch (PDOException $e) {
                return $e;
            }
        }

        private function checkAddEvent($eventname, $eventstart, $eventend)
        {
            return ($eventname !='' && $eventstart !='' && $eventend !='') ? true : false;
        }

        private function getDateToday()
        {
            return date('Y-m-d');
        }

        private function checkRegister($fullname, $username, $password)
        {
            return ($fullname != '' && $username != '' && $password != '') ? true : false;
        }

        private function checkLogin($username, $password)
        {
            return ($username != '' && $password != '') ? true : false;
        }

        private function loginQuery()
        {
            return "SELECT * FROM `accounts` WHERE `username` = ? AND `password` = ?";
        }

        private function registerQuery()
        {
            return "INSERT INTO `accounts` (`fullname`, `username`, `password`, `date_created`, `date_updated`) VALUES (?,?,?,?,?)";
        }

        private function getAccountQuery()
        {
            return "SELECT * FROM `accounts` WHERE `id` = ?";
        }

        private function insertEventsQuery()
        {
            return "INSERT INTO `events` (`user_id`, `event_name`, `event_start`, `event_end`) VALUES (?,?,?,?)";
        }

        private function getAllEvents()
        {
            return "SELECT * FROM `events` WHERE `user_id` = ?";
        }

        private function deleteEventQuery()
        {
            return "DELETE FROM `events` WHERE `user_id` = ? AND `id` = ?";
        }

        private function updatePasswordQuery()
        {
            return "UPDATE `accounts` SET `password` = ?, `date_updated` = ? WHERE `id` = ?";
        }

        private function updateUsernameQuery()
        {
            return "UPDATE `accounts` SET `username` = ?, `date_updated` = ? WHERE `id` = ?";
        }

        private function updateFullnameQuery()
        {
            return "UPDATE `accounts` SET `fullname` = ?, `date_updated` = ? WHERE `id` = ?";
        }
    }

?>