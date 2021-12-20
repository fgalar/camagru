<?php

    class Like extends Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function count_picture_likes($picture_id)
        {
            return $this->count_element(['picture_id' => $picture_id]);
        }

        public function is_current_user_liked($picture_id, $user)
        {
            return $this->select_one(
                ['user_id' => $user->id,
                'picture_id' => $picture_id]) ? True : False;
        }

        public function add_like($picture_id, $user_id)
        {
            return $this->insert(
                ['user_id' => $user_id,
                 'picture_id' => $picture_id]
            );
        }

        public function delete_like($picture_id, $user_id)
        {
            return $this->delete(
                ['user_id' => $user_id,
                 'picture_id' => $picture_id]
            );
        }
    }