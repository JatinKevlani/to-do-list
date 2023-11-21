const tasksJSON = localStorage.getItem("tasks");
let tasks = tasksJSON ? JSON.parse(tasksJSON) : [];

function update() {
    if (tasks.length == 0) {
        tableBody.innerHTML = `No task found!`;
        return;
    }
    tableBody.innerHTML = ``;
    for (let i = 0; i < tasks.length; i++) {
        tableBody.innerHTML += `
        <tr>
            <th>${i + 1}</th>
            <td>${tasks[i]["taskTitle"]}</td>
            <td>${tasks[i]["taskDesc"]}</td>
            <td><button class="btn" onclick="deleteTask(${i});">Delete</button></td>
        </tr>
        `;
    }
}

update();

add.addEventListener("click", () => {
    input.innerHTML = `<br><label for="title">Title</label>
    <input type="text" name="title" id="title"><br> 
    <label for="desc">Description</label>
    <input type="text" name="desc" id="desc"><br>
    <button class="btn" id="addNewTask">Submit</button>
    <button class="btn" id="cancel">Cancel</button>`;
    addNewTask.addEventListener("click", () => {
        const title = document.getElementById("title").value;
        const desc = document.getElementById("desc").value;
        if (!title) {
            alert("Cannot add empty title!");
            input.innerHTML = "";
            return;
        }
        if (!desc) {
            if (confirm("Add new task without adding description")) {
                let newTask = {
                    taskTitle: title,
                    taskDesc: "-"
                };
                tasks.push(newTask);
                localStorage.setItem("tasks", JSON.stringify(tasks));
                update();
                input.innerHTML = "";
                return;
            }
            input.innerHTML = ``;
            return;
        }
        let newTask = {
            taskTitle: title,
            taskDesc: desc
        };
        tasks.push(newTask);
        localStorage.setItem("tasks", JSON.stringify(tasks));
        update();
        input.innerHTML = "";
    });
    cancel.addEventListener("click", () => {
        input.innerHTML = "";
    });
});

deleteAll.addEventListener("click", () => {
    if (tasks.length == 0) {
        alert("No tasks exists!");
        return;
    }
    if (confirm("Are you sure to delete all tasks?")) {
        localStorage.clear();
        tasks = [];
    }
    update();
});

function deleteTask(index) {
    tasks.splice(index, 1);
    localStorage.setItem("tasks", JSON.stringify(tasks));
    update();
}