const mongoose = require("mongoose");


const courseSchema = new mongoose.Schema({
    code: String,
    name: String,
    period: Number
}, {versionKey: false});

const Course = mongoose.model("Course", courseSchema);


exports.Course = Course;
