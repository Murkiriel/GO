import json
import sys

from sinesp_client import SinespClient

sc = SinespClient(proxy_address=sys.argv[1], proxy_port=sys.argv[2])
plate = sys.argv[3]
result = sc.search(plate)
json_result = json.dumps(result)
print(json_result)
