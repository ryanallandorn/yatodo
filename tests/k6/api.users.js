import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '30s', target: 500 }, // ramp up to 50 users
        { duration: '1m', target: 500 },  // stay at 50 users
        { duration: '30s', target: 0 },  // ramp down to 0 users
    ],
};

export default function () {
    let res = http.get('http://127.0.0.1:8000/api/get/users?limit=100'); // Replace with your endpoint
    check(res, {
        'is status 200': (r) => r.status === 200,
    });
    sleep(1);
}
