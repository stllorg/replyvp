<?php

class MessageService
{
    private RatingRepository $ratingRepository;
    private ProductRepository $productRepository;
    private UserRepository $userRepository;

    public function __construct(RatingRepository $ratingRepository, ProductRepository $productRepository, UserRepository $userRepository)
    {
        $this->ratingRepository = $ratingRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    public function rateProduct(int $productId, int $userId, int $ratingValue): ?Rating
    {
        $product = $this->productRepository->findById($productId);
        $user = $this->userRepository->findById($userId);

        if (!$product) {
            throw new \Exception("Product not found.");
        }
        if (!$user) {
            throw new \Exception("User not found.");
        }
        if ($ratingValue < 1 || $ratingValue > 5) { // Example rating range
            throw new \Exception("Invalid rating value.");
        }

        $rating = new Rating(product_id: $productId, user_id: $userId, rating: $ratingValue);
        return $this->ratingRepository->save($rating);
    }

    public function getAverageRating(int $productId): float
    {
        return $this->ratingRepository->getAverageRatingForProduct($productId);
    }
}