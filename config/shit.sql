/*ALTER TABLE user_badges DROP CONSTRAINT user_badges_user_id_foreign;*/
ALTER TABLE username_history DROP CONSTRAINT username_history_user_id_foreign;
ALTER TABLE user_badges DROP CONSTRAINT user_badges_granter_id_foreign;
TRUNCATE TABLE users;
ALTER TABLE user_badges ADD CONSTRAINT user_badges_user_id_foreign FOREIGN KEY(user_id);
ALTER TABLE user_badges ADD CONSTRAINT user_badges_granter_id_foreign FOREIGN KEY(granter_id);
ALTER TABLE  username_history ADD CONSTRAINT username_history_user_id_foreign FOREIGN KEY(user_id);