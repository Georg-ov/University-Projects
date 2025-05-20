<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;

class AssociationsTest extends TestCase
{
    /**
     * Test User to Address relationship.
     *
     * @return void
     */
    public function testAssociationUserAddress()
    {
        $user = new User();
        $user->name = 'John Doe';
        $user->username = 'jhon_doe';
        $user->email = 'john@example.com';
        $user->password = '123';
        $user->age = 25;
        $user->role_type = 'TEACHER';
        $user->subscription_type = 'FREEMIUM';
        $user->save();
        
        $address = new Address();
        $address->street = '123 Main St';
        $address->city = 'New York';
        $address->province = 'NY';
        $address->postal_code = 10001;
        $address->country = 'USA';
        $address->user_id = $user->id;

        $address->save();

        $this->assertEquals($address->user->name, 'John Doe');
        $this->assertEquals($user->address->city, 'New York');

        $address->delete();
        $user->delete();
    }

    /**
     * Test Course to User and Category relationships.
     *
     * @return void
     */
    public function testAssociationCourseUserCategory()
    {
        $user = new User();
        $user->name = 'John Doe';
        $user->username = 'jhon_doe2';
        $user->email = 'john@example.com';
        $user->password = '123';
        $user->age = 25;
        $user->role_type = 'TEACHER';
        $user->subscription_type = 'FREEMIUM';
        $user->save();

        $category = new Category();
        $category->name = 'Programming';
        $category->description = 'This is a programming category';
        $category->image_file_name = 'image.png';
        $category->save();

        $course = new Course();
        $course->name = 'Laravel Basics';
        $course->description = 'This is a course description';
        $course->image_file_name = 'image.jpg';
        $course->publish_date = new \DateTime();
        $course->visibility = 0;
        $course->user_id = $user->id;
        $course->category_id = $category->id;
        $course->save();

        $lesson = new Lesson();
        $lesson->name = 'Introduction to Python';
        $lesson->description = 'This is a lesson description';
        $lesson->video_file_name = 'videon.mp4';
        $lesson->course_id = $course->id;
        $lesson->save();

        $this->assertEquals($course->user->name, 'John Doe');
        $this->assertEquals($course->category->name, 'Programming');
        $this->assertEquals($category->courses[0]->name, 'Laravel Basics');
        
        $lesson->delete();
        $course->delete();
        $category->delete();
        $user->delete();
    }

    /**
     * Test Lesson to Course relationship.
     *
     * @return void
     */
    public function testAssociationLessonCourse()
    {
        $user = new User();
        $user->name = 'John Doe';
        $user->username = 'jhon_doe3';
        $user->email = 'john@example.com';
        $user->password = '123';
        $user->age = 25;
        $user->role_type = 'TEACHER';
        $user->subscription_type = 'FREEMIUM';
        $user->save();

        $category = new Category();
        $category->name = 'Wellness';
        $category->description = 'This is a programming category';
        $category->image_file_name = 'image.png';
        $category->save();

        $course = new Course();
        $course->name = 'Wellness 101';
        $course->description = 'This is a course description';
        $course->image_file_name = 'image.jpg';
        $course->publish_date = new \DateTime();
        $course->visibility = 0;
        $course->user_id = $user->id;
        $course->category_id = $category->id;
        $course->save();

        $lesson = new Lesson();
        $lesson->name = 'Introduction to Python';
        $lesson->description = 'This is a lesson description';
        $lesson->video_file_name = 'videon.mp4';
        $lesson->course_id = $course->id;
        $lesson->save();

        $this->assertEquals($lesson->course->name, 'Wellness 101');
        $this->assertEquals($course->lessons[0]->name, 'Introduction to Python');

        
        $lesson->delete();
        $course->delete();
        $category->delete();
        $user->delete();
    }
}
