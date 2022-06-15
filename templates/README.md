### Protgres in local
- `sudo docker exec -it bankaccounttest_db_1 bash`
- `psql -U postgres db`
- `INSERT INTO public.user (id,account_id,username) VALUES (1,NULL,'johnDeo');`