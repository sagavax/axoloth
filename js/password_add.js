function LogonType(type_id) {
    const credentials = {
        0: { user_name: "", password: "" },
        1: { user_name: "tmisura@gmail.com", password: "Thomas$.pa$$w0rd" },
        2: { user_name: "tmisura@gmail.com", password: "YTOKaQHOhXO" },
        3: { user_name: "ftb_axoloth", password: "g1sqrrprfwb5yhh" },
        4: { user_name: "tmisura", password: "Toma$.pa$$w0rd" },
        5: { user_name: "tmisura@gmail.com", password: "28d72uvyesn2eka" },
        6: { user_name: "sagavax", password: "sxzec4yytboelcj" },
        7: { user_name: "tmisura@gmail.com", password: "642vol5mmedfrxs" }
    };

    const userCredentials = credentials[type_id];
    if (userCredentials) {
        document.new_password.user_name.value = userCredentials.user_name;
        document.new_password.password.value = userCredentials.password;
    } else {
        // Default behavior if an invalid `type_id` is provided
        document.new_password.user_name.value = "";
        document.new_password.password.value = "";
    }
}