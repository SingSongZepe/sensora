package mongo_

import (
	"context"
	"log"
	"sync"
	"fmt"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
	
	// "server/utils"
	"server/value"
)

// outer function use to get pool
func GetPool() *MongoPool {
	return mongoPool
}

// connect to mongodb
type MongoPool struct {
	connectionPool *sync.Pool
}

var mongoPool *MongoPool

func init() {
	mongoPool = newPool()
	fmt.Println(value.Sslog + value.NOR_ + "mongodb init")
}

func newPool() *MongoPool {
	mongoPool := &MongoPool{
		connectionPool: &sync.Pool{
			New: func () interface{} {
				client, err := mongo.Connect(context.TODO(), options.Client().ApplyURI("mongodb://localhost:27017"))
				if err != nil {
					log.Fatal(err)
				}
				return client
			},
		},
	}
	return mongoPool
}
// get connection
func (p *MongoPool) GetConnection() *mongo.Client {
	return p.connectionPool.Get().(*mongo.Client)
}
// release connection
func (p *MongoPool) ReleaseConnection(client *mongo.Client) {
	p.connectionPool.Put(client)
}