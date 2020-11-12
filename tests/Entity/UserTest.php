<?php

namespace App\Tests;

use DateTime;
use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /**
     * Undocumented function
     *
     * @return User
     */
    public function getEntity(): User
    {
        $user = (new User())
            ->setEmail('john-doe@example.com')
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setPassword('0123456789')
            ->setLogin('johnny')
            ->setDescription('cool guy')
            ->setCreatedAt(new DateTime())
            ->setAvatar('avatar.png')
            ->setConfirmationToken(null)
            ->setTokenEnabled(true);

        $user->initializeSlug();
        return $user;
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param integer $number
     * @return void
     */
    public function assertHasErrors(User $user, int $number = 0)
    {
        // Act
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        // Assert
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * Test the validity of the User entity
     * It should validate constraints specified inside the Entity as annotations
     *
     * @return void
     */
    public function testValidUserEntity()
    {
        return $this->assertHasErrors($this->getEntity());
    }

    /**
     * Test the invalidity of the User entity
     * It should returns 4 validation errors
     * 
     * @return void
     */
    public function testInvalidUserEntity()
    {
        return $this->assertHasErrors(
            $this->getEntity()
                ->setEmail('john-doe@john-com')
                ->setFirstname('Jo')
                ->setLastname('Do')
                ->setPassword('abc'),
            4
        );
    }

    public function testInvalidBlankEmail()
    {
        return $this->assertHasErrors(
            $this->getEntity()->setEmail(''),
            1
        );
    }

    public function testInvalidBlankFirstName()
    {
        return $this->assertHasErrors(
            $this->getEntity()->setFirstname(''),
            2
        );
    }

    public function testInvalidBlankLastName()
    {
        return $this->assertHasErrors(
            $this->getEntity()->setLastname(''),
            2
        );
    }

    public function testInvalidBlankLogin()
    {
        return $this->assertHasErrors(
            $this->getEntity()->setLastname(''),
            2
        );
    }

    public function testValidUserSlug()
    {
        return $this->assertSame(
            'john-doe',
            $this->getEntity()->getSlug()
        );
    }

    public function testInvalidUsedEmail()
    {
        return $this->assertHasErrors(
            $this->getEntity()->setEmail('supraderp@gmail.com'),
            1
        );
    }
}