<?php

    class Picture extends Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function count()
        {
            return (int) $this->count_element();
        }

        public function get_limited_posts($field, $limit, $offset)
        {
            $pdo = $this->get_pdo();
            $table = strtolower(get_class($this)) . 's';

            $sql = "SELECT " . implode(', ', $field) . " FROM $table ORDER BY id DESC LIMIT $limit OFFSET $offset ";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();

        }

        public function add_picture($user_id, $path)
        {
            return $this->insert(
                ['user_id' => $user_id,
                 'path' => $path]
            );
        }

        public function delete_picture($picture, $user_id)
        {
            return $this->delete(
                ['user_id' => $user_id,
                 'path' => $picture]
            );
        }

        public function get_author_picture($picture_id)
        {
            $sql_select = "SELECT users.login, users.mail, users.sendmail FROM pictures ";
            $sql_join = "JOIN users ON pictures.user_id = users.id ";
            $sql_cond = "WHERE pictures.id = $picture_id";
            $pdo = $this->get_pdo();
            $stmt = $pdo->query($sql_select . $sql_join . $sql_cond);
            return $stmt->fetch();
        }

        public function get_all_pictures_by_user($user_id)
        {
            return $this->select_all(['user_id' => $user_id]);
        }

    }