CREATE table categories  (
id INTEGER not null primary key,
categoty VARCHAR(16),
parent_id INTEGER not null,
level integer not null,
left_key INTEGER not null,
right_key INTEGER not null,
);