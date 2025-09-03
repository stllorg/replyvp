.PHONY: start

start:
	@echo "Running the 'start' command..."
	./generate_new_jwt_rsa_key_pair.sh
	docker compose up --build -d
	@echo "Application started successfully."

.PHONY: stop

stop:
	@echo "Stopping the application..."
	docker compose down
	@echo "Application stopped."
