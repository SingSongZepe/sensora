package utils

import (
	"context"
	"math/rand"

	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"

	"server/mongo_"
	"server/object"
	"server/value"
)

func UidGenerator() int {
	// uid from 1000000000
	//       to 2147483647

	for {
		uid := rand.Intn(value.UID_MAX-value.UID_MIN+1) + value.UID_MIN

		mongoPool := mongo_.GetPool()
		client := mongoPool.GetConnection()
		defer mongoPool.ReleaseConnection(client)

		coll_user := client.Database(value.SENSORA_).Collection(value.USER_)
		filter := bson.D{{Key: value.UID_, Value: uid}}

		var user_ object.User_
		err := coll_user.FindOne(context.TODO(), filter).Decode(&user_)
		if err == mongo.ErrNoDocuments {
			// if uid not found
			return uid
		}
	}
}
