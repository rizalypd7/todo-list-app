const API_URL = 'http://localhost/todo-app/api/api.php';

async function fetchTodos() {
    const response = await fetch(API_URL);
    const todos = await response.json();

    const todoList = document.getElementById('todo-list');
    todoList.innerHTML = '';

    todos.forEach(todo => {
        const li = document.createElement('li');
        li.innerHTML = `
            <strong>${todo.title}</strong>
            <p>${todo.description}</p>
            <button onclick="deleteTodo(${todo.id})">Hapus</button>
        `;
        todoList.appendChild(li);
    });
}

async function deleteTodo(id) {
    await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
    fetchTodos();
}

document.getElementById('todo-form').addEventListener('submit', async event => {
    event.preventDefault();

    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, description, status: 'pending' })
    });

    fetchTodos();
});

fetchTodos();
