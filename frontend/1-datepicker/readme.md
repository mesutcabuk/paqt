# 📌 Before you start, I've used AI to auto generate this readme for the basic datepicker solutions!

# 📌 Rental Form - One-Page HTML & Vue.js Solutions

This folder contains **two implementations** of a simple rental form:
1. **One-Page HTML Solution** (Pure JavaScript)
2. **Vue.js Solution** (Simple and Cleaner)

Both implementations allow users to select a **start date, end date, and an object**, then check availability.

---

## 📌 Pre-Assumptions

- This is a **frontend-only project**, no backend logic.
- The **availability check is simulated** (random response).
- The **end date must be later than the start date**.
- The **form must be fully completed** before submission.

---

## 📌 One-Page HTML Solution

### 📌 How to Use?
1. Open `html-datepicker.html` in any **modern web browser**.
2. Fill in the form fields and click **"Check Availability"**.
3. If the input is invalid, an error message will appear.
4. A message will indicate whether the object is **available or not**.

### 📌 Features:
✅ No dependencies, **pure HTML, CSS, and JavaScript**  
✅ Simple **form validation** (empty fields, date logic)  
✅ Simulated **availability check**  

---

## 📌 Vue.js Solution

### 📌 How to Use?
1. Open `vu-js-datepicker.html` in a **modern web browser**.
2. Vue.js will handle the **form validation** dynamically.
3. Click **"Check Availability"** to simulate API behavior.
4. The result will indicate **availability status**.

### 📌 Features:
✅ Uses **Vue.js CDN** (no build tools required)  
✅ More **reactive and structured** compared to pure JavaScript  
✅ Simple and easy to **extend with real API**  

