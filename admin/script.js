
  let currentType = ''; 
  let editRow = null;

  function showSection(id) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    document.getElementById(id).classList.add('active');
  }

  // Dropdown toggle
  function toggleDropdown() {
    let dd = document.getElementById("profileDropdown");
    dd.style.display = dd.style.display === "block" ? "none" : "block";
  }

  // Profile popup
  function openProfile(mode) {
    document.getElementById("profileOverlay").style.display = "flex";
    document.getElementById("profileTitle").innerText = mode === "view" ? "Profile" : "Edit Profile";
    document.getElementById("adminName").disabled = (mode === "view");
    document.getElementById("adminEmail").disabled = (mode === "view");
    document.getElementById("profileDropdown").style.display = "none";
  }
  function closeProfile() {
    document.getElementById("profileOverlay").style.display = "none";
  }
  function saveProfile() {
    alert("Profile updated: " + document.getElementById("adminName").value);
    closeProfile();
  }

  // Existing popups
  function openPopup(type, row=null) {
    currentType = type;
    editRow = row;
    document.getElementById("popupOverlay").style.display = "flex";
    let form = document.getElementById("form");
    form.innerHTML = "";

    if (type === 'user') {
      document.getElementById("popupTitle").innerText = row ? "Edit User" : "Add User";
      form.innerHTML = `
        <input type="text" id="username" placeholder="Name" value="${row ? row.cells[1].innerText : ''}" required>
        <input type="email" id="useremail" placeholder="Email" value="${row ? row.cells[2].innerText : ''}" required>
        <input type="text" id="userrole" placeholder="Role" value="${row ? row.cells[3].innerText : ''}" required>
      `;
    } else if (type === 'blood') {
      document.getElementById("popupTitle").innerText = row ? "Edit Blood Unit" : "Add Blood Unit";
      form.innerHTML = `
        <input type="text" id="bloodtype" placeholder="Blood Type" value="${row ? row.cells[1].innerText : ''}" required>
        <input type="number" id="bloodunits" placeholder="Units" value="${row ? row.cells[2].innerText : ''}" required>
        <input type="date" id="bloodexpiry" value="${row ? row.cells[3].innerText : ''}" required>
      `;
    }
  }

  function closePopup() {
    document.getElementById("popupOverlay").style.display = "none";
  }

  function saveData() {
    if (currentType === 'user') {
      let name = document.getElementById("username").value;
      let email = document.getElementById("useremail").value;
      let role = document.getElementById("userrole").value;
      let table = document.getElementById("userTable").getElementsByTagName("tbody")[0];

      if (editRow) {
        editRow.cells[1].innerText = name;
        editRow.cells[2].innerText = email;
        editRow.cells[3].innerText = role;
      } else {
        let id = table.rows.length + 1;
        let row = table.insertRow();
        row.innerHTML = `<td>${id}</td><td>${name}</td><td>${email}</td><td>${role}</td>
          <td>
            <button class="btn-edit" onclick="openPopup('user', this.closest('tr'))">Edit</button>
            <button class="btn-delete" onclick="this.closest('tr').remove()">Delete</button>
          </td>`;
      }
    }
    else if (currentType === 'blood') {
      let type = document.getElementById("bloodtype").value;
      let units = document.getElementById("bloodunits").value;
      let expiry = document.getElementById("bloodexpiry").value;
      let table = document.getElementById("bloodTable").getElementsByTagName("tbody")[0];

      if (editRow) {
        editRow.cells[1].innerText = type;
        editRow.cells[2].innerText = units;
        editRow.cells[3].innerText = expiry;
      } else {
        let id = table.rows.length + 1;
        let row = table.insertRow();
        row.innerHTML = `<td>${id}</td><td>${type}</td><td>${units}</td><td>${expiry}</td>
          <td>
            <button class="btn-edit" onclick="openPopup('blood', this.closest('tr'))">Edit</button>
            <button class="btn-delete" onclick="this.closest('tr').remove()">Delete</button>
          </td>`;
      }
    }
    closePopup();
  }