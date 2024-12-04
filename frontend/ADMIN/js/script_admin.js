

  document.addEventListener("DOMContentLoaded", () => {
    const adminInfo = document.querySelector(".admin-info");
    const dropdownMenu = document.querySelector(".dropdown-menu");
  
    // Thêm sự kiện click vào phần tử ".or-info"
    adminInfo.addEventListener("click", () => {
      // Toggle trạng thái hiển thị dropdown menu
      dropdownMenu.style.display =
        dropdownMenu.style.display === "flex" ? "none" : "flex";
  
      // Xoay mũi tên
      const arrowIcon = adminInfo.querySelector(".bi-chevron-down");
      if (dropdownMenu.style.display === "flex") {
        arrowIcon.style.transform = "rotate(180deg)";
      } else {
        arrowIcon.style.transform = "rotate(0deg)";
      }
    });
  
    // Đóng dropdown menu khi nhấp ra ngoài
    document.addEventListener("click", (event) => {
      if (!adminInfo.contains(event.target)) {
        dropdownMenu.style.display = "none";
        const arrowIcon = adminInfo.querySelector(".bi-chevron-down");
        arrowIcon.style.transform = "rotate(0deg)";
      }
    });
  });
  