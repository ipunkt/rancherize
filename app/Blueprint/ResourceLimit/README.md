# ResourceLimit

Set resource limits for your service

## Options
- resource-limit.cpu
  - full: no limit
  - high: cap at 70% cpu
  - low: cap at 30% cpu
- resource-limit.memory memory in bytes available to the container
  - Suffix `g` or `G` will convert the number to gibibytes
  - Suffix `m` or `M` will convert the number to mibibytes
  - Suffix `k` or `K` will convert the number to kibibytes
  
Note that hitting the cpu limit will only throttle your applications speed. Hitting the memory limit will prevent your
appliaction from allocating memory which will most likely kill the appliaction.

## Examples
Set high cpu usage and 4 GB ram cap
```json
"resource-limit":{
	"cpu":"high",
	"memory":4g
}
```