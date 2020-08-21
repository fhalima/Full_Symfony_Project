
@echo off
start cmd /k php bin/console server:run
start cmd /k yarn encore dev --watch
exit
