<?php
namespace HP\Bundle\EmailApiBundle\Request;

interface GetInboxMessagesRequestBuilderInterface
{
    public function create();
    public function setMaxResults($maxResults);
    public function build();
}
