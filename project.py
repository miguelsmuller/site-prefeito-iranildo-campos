from typing import Optional
import typer
import subprocess
import os

app = typer.Typer(add_completion=False)

@app.command()
def install():
  os.system("docker-compose up --detach wordpress database manage --force-recreate --build")

@app.command()
def idata():
  os.system("docker-compose run --rm cli sh -c 'sh /docker/idata.sh'")

@app.command()
def edata():
  os.system("docker-compose run --rm cli sh -c 'sh /docker/edata.sh'")

@app.command()
def uninstall(
  rm_images: bool = typer.Option(False,
    help="Remove all images used by any service",
    prompt="Remove images?"
  ),
  rm_volumes: bool = typer.Option(True,
    help="Remove named volumes attached to containers",
    prompt="Remove volumes?"
  )):
    images = "--rmi all" if (rm_images == True) else ""
    volumes = "--volumes" if (rm_volumes == True) else ""

    os.system(f"docker-compose down {volumes} {images}")
    os.system(f"docker volume prune -f")

@app.command()
def start():
  os.system("docker-compose restart")

@app.command()
def stop():
  os.system("docker-compose stop")

@app.command()
def exec(exec: str = typer.Argument(..., help='Comand to be insert into container')):
  os.system("docker-compose run --rm cli {$exec}")

if __name__ == "__main__":
  try:
    status = subprocess.check_output('docker ps', shell=True)
  except:
    status=""

  if not status:
    print("docker must be running")
  else:
    app()
