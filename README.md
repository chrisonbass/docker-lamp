# LAMP as Docker containers
A simple LAMP stack using docker-compose to run apache, php, and mysql in their own containers

## Build or Rebuild only
Creates docker images from the Dockerfiles in this repo.  If you change a Dockerfile, run this command to rebuild the image.
```
docker-compose build
```

## Build and Start Services
Creates the docker images and then create the docker containers (running services) from the docker-images
```
docker-compose up
```

## Stop Running Containers (services)
From the same command-line window, press CTRL+SHIFT+C to stop the services.  Then run the below command when it finishes stopping
```
docker-compose down
```
