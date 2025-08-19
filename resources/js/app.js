import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)

// helpers
function generateNickname() {
    const nickname_req_length = 6;
    const first_name = (document.getElementById('first_name').value).toLowerCase();
    const last_name = (document.getElementById('last_name').value).toLowerCase();
    const nickname_el = document.getElementById('nickname');

    if (first_name.length <= 0 || last_name.length <= 0 || nickname_el.value.length > 0) return;

    let nickname = first_name.slice(0, 3) + last_name.slice(0, 3);
    if (nickname.length < nickname_req_length) {
        let count = nickname_req_length - nickname.length;
        for (let i = 0; i < count; i++) {
            nickname += Math.floor(Math.random() * 10); // Append a random digit
        }
    }
    document.getElementById('nickname').value = nickname;
}

function studentGroupAssignment({available, selected}) {
    return {
        available: available || [],
        selected: selected || [],
        selectUser(index) {
            this.selected.push(this.available.splice(index, 1)[0]);
        },
        removeUser(index) {
            this.available.push(this.selected.splice(index, 1)[0]);
        }
    }
}



window.AppHelpers = {};
window.AppHelpers.generateNickname = generateNickname; // Expose the function to the global scope
window.AppHelpers.studentGroupAssignment = studentGroupAssignment;

window.Alpine = Alpine;
Alpine.start();
