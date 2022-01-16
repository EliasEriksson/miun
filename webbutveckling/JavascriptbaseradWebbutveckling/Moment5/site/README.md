# Requirements for running
Instruction for running both the express server and the React development server.

## Express
1. To run the backend api a .credentials.json file with the database details is required to be placed in the `site/` \
   directory the file contents should be
   ```json
   {
     "user": "dbUser",
     "pwd": "dbUserPwd",
     "host": "localhost",
     "port": 27017,
     "db": "dbName"
   }
   ```
   Where `user`, `pwd` and `db` is changed to an existing user on your mongoDB Database.
2. Run `npm install` inside `site/` directory.
3. Run `node_modules/.bin/nodemon index.js` inside the `site/` directory.

## React
1. Run `npm install` in the `site/client/` directory.
2. Run `npm start` in the `site/client/` directory.