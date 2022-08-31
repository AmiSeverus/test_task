create table users (
id serial primary key,
name VARCHAR(225) not NUll
);

create table friends (
	user_id integer REFERENCES users (id) not NULL,
	friend_id integer REFERENCES users (id) not NUll,
	primary key (user_id, friend_id)
);

select user_id from friends 
group by (user_id)
having count(*) > 5
order by user_id;

select f1.user_id , f1.friend_id from friends f1
inner join friends f2
on f1.user_id = f2.friend_id and f1.friend_id = f2.user_id;