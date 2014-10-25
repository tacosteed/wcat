#!/bin/sh

echo_start()
{
    echo `/bin/date '+%Y/%m/%d %T'` START $1 PID $2
}

echo_end()
{
    echo `/bin/date '+%Y/%m/%d %T'` END $1 PID $2
}
