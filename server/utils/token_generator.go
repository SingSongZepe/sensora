package utils

import (
	"os/exec"
	"errors"
	"fmt"

	"server/object"
	"server/value"
)

func TokenGenerator(uid int) (object.Token_, error) {
	cmd := exec.Command(value.PY_, value.PY_VERSION, value.PY_TOKEN_GENERATOR, fmt.Sprintf("%d", uid))
	output, err := cmd.Output()
	if err != nil {
		return object.Token_{}, 
		errors.New("error generating token")
	}
	return object.Token_{
		Uid: uid,
		TokenStr: string(output),
	}, nil
}