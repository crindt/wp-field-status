package:
	$(eval NAME := $(shell basename `pwd`))
	( cd .. && zip -r `dirname ${NAME}/${NAME}.zip` ${NAME}.zip ${NAME} --include "${NAME}/${NAME}.php")

.FORCE: package
