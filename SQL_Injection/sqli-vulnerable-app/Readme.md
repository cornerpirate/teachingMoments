## Setup the Docker Image

This requires Docker to work. Please goolge how to install Docker on your OS and when you get that working come back to this.

The demo uses an existing lamp stack Docker image. This has a Ubuntu base, Apache2, MySQL and PHP baked into the image.
To download and run the vulnerable application first enter the ```sqli-vulnerable-app``` directory and then execute the
following docker commad:

```bash
docker run -i -t -p "8888:80" -v ${PWD}/app:/app -v ${PWD}/mysql:/var/lib/mysql mattrayner/lamp:latest
```

This will expose the app on TCP port 8888 locally. It will mount the program code from the ./app folder, and create a persistent MySQL database folder.

Browse to the application http://localhost:8888/ and then click on the button labelled "RESET" to setup the database before proceeding. Or you will
get error messages when you browse to the vulnerable pages saying the user does not exist.

# Intro Demo

* [Slides from talk](../intro/SQLi-Introduction.pdf)
* [Demo Script and Notes](../intro/Intro-Demo.md)

# Round2 Demo

-- This is work in progress so placeholder links to content.
* [Slides from talk](..//intro/SQLi-round2.pdf)
* [Demo Script and Notes](../round2/Round2-Demo.md)
