package utils

import (
	"os/exec"
	"errors"

	"server/value"
)

// utils to decrypt RSA
func Decrypt(userinfo []byte) ([]byte, error) {
	cmd := exec.Command(value.PY_, value.PY_VERSION, value.PY_DECRYPT_SCRIPT, string(userinfo))
	output, err := cmd.Output()
	if err != nil {
		return []byte(""), errors.New("error run decrypt script")
	}
	return output, nil
}