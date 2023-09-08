SHELL := /bin/bash

build:
	docker-compose build

build_log:
	docker-compose up > >(tee docker-compose-build.log) 2> >(tee docker-compose-pull.log >&2)

up:
	docker-compose up -d

down:
	docker-compose down

kill:
	docker kill $$(docker ps -q)

clean:
	docker system prune --all --force
