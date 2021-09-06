
use samtestdb;

db.createUser(
  {
    user: "user_db",
    pwd: "user_db_pwd", // or cleartext password
     roles: [
       { role: "readWrite", db: "samtestdb" }
    ]
  }
);


db.samtestdb.insert({name: "Ada Lovelace", age: 205});



db.collection.find( { name: { $gt: 4 } } )

-------------------------------------------------------
