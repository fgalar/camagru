<?php

    class Comment extends Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function get_comments_for_post($picture_id)
        {
            $sql_select = "SELECT users.login, comments.timestamp, comments.comment FROM `comments` ";
            $sql_join = "JOIN `users` ON comments.user_id = users.id ";
            $sql_cond = "WHERE comments.picture_id = $picture_id ORDER BY comments.timestamp DESC";
            $pdo = $this->get_pdo();
            $stmt = $pdo->query($sql_select . $sql_join . $sql_cond);
            return $stmt->fetchAll();
        }

        public function count_picture_comments($picture_id)
        {
            return $this->count_element(['picture_id' => $picture_id]);
        }

        public function add_comment($user_id, $picture_id, $comment)
        {
            $this->insert([
                'user_id'       => $user_id,
                'picture_id'    => $picture_id,
                'comment'       => $comment
            ]);
            $sql_select = "SELECT users.login, comments.timestamp, comments.comment FROM `comments` ";
            $sql_join = "JOIN `users` ON comments.user_id = users.id ";
            $sql_cond = "WHERE comments.picture_id = $picture_id ORDER BY comments.timestamp DESC";
            $pdo = $this->get_pdo();
            $stmt = $pdo->query($sql_select . $sql_join . $sql_cond);
            return $stmt->fetch();
        }

    }