package check

import (
	"context"
	"errors"
	"fmt"
	"os/exec"
	"strconv"
	"strings"

	"go.mongodb.org/mongo-driver/bson"

	"server/mongo_"
	"server/object"
	"server/utils"
	"server/value"
)

// utils that decrypt the token string into object.Token
// and check the token is exists in the database and check the time and the sign is invalid or not
func CheckToken(token_str string) (bool, error) { //
	cmd := exec.Command(value.PY_, value.PY_VERSION, value.PY_TOKEN_CHECKER, token_str)
	output, err := cmd.Output()
	if err != nil {
		utils.Le("error parsing token into string")
		return false, 
		errors.New("error parsing token into string")
	}
	output_lines := strings.Split(string(output), "\r\n")
	
	// uid 
	uid := -1
	for idx, line := range output_lines {
		if idx == 0 {
			if line == "False" {
				return false,
				errors.New("token invalid")
			}
		} else if idx == 1 {
			uid, err = strconv.Atoi(line)
			if err != nil {
				return false,
				errors.New("uid invalid")
			}
			utils.Ln(fmt.Sprintf("user uid: %d", uid))
		} else {
			break
		}
	}

	mongoPool := mongo_.GetPool()
	client := mongoPool.GetConnection()
	defer mongoPool.ReleaseConnection(client)

	coll_token := client.Database(value.SENSORA_).Collection(value.TOKEN_)
	filter := bson.D{{Key: value.UID_, Value: uid}}
	
	var token_ object.Token_
	err = coll_token.FindOne(context.TODO(), filter).Decode(&token_)
	if err != nil {
		utils.Le("user not found")
		return false,
		errors.New("user not found")
	}

	if token_.TokenStr != token_str {
		utils.Le("user and token not match")
		return false,
		errors.New("user and token not match")
	}

	return true, nil
}